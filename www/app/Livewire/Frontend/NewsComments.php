<?php

namespace App\Livewire\Frontend;

use App\Models\Comment;
use App\Models\News;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NewsComments extends Component
{
    public $newsId;
    public $newComment = '';
    public $replyingTo = null;
    public $replyComment = '';

    public function mount($newsId)
    {
        $this->newsId = $newsId;
    }

    public function postComment()
    {
        $this->saveComment($this->newComment, null);
        $this->newComment = '';
    }

    public function postReply($parentId)
    {
        $this->saveComment($this->replyComment, $parentId);
        $this->replyComment = '';
        $this->replyingTo = null;
    }

    public function setReplyingTo($commentId)
    {
        $this->replyingTo = $commentId;
        $this->replyComment = '';
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyComment = '';
    }

    private function saveComment($content, $parentId = null)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        
        // Verifica se o usuário não perdeu suas 3 vidas
        if ($user->banned_from_comments) {
            session()->flash('error', 'Sua conta está permanentemente bloqueada de comentar devido a repetidas violações de nossas políticas.');
            return;
        }

        $content = trim($content);
        if (empty($content)) {
            return;
        }

        // MOTOR DE SHADOWBAN
        $isShadowbanned = false;
        $shadowbanReason = null;
        
        $bannedWordsStr = Setting::get('banned_words', '');
        $bannedWords = array_map('trim', explode(',', strtolower($bannedWordsStr)));
        
        $contentLower = strtolower($content);

        foreach ($bannedWords as $word) {
            if (!empty($word) && str_contains($contentLower, $word)) {
                $isShadowbanned = true;
                $shadowbanReason = "Filtro Automático ativado pela palavra censurada: '{$word}'";
                break;
            }
        }

        Comment::create([
            'user_id' => $user->id,
            'news_id' => $this->newsId,
            'parent_id' => $parentId,
            'content' => $content,
            'is_shadowbanned' => $isShadowbanned,
            'shadowban_reason' => $shadowbanReason,
            'is_hidden' => false,
        ]);

        if ($isShadowbanned) {
            $user->increment('comment_strikes');
            // Sistema de Strikes: 3 Vidas
            if ($user->comment_strikes >= 3) {
                $user->update(['banned_from_comments' => true]);
            }
        }

        session()->flash('success_comment', 'Comentário publicado!');
    }

    public function render()
    {
        $currentUserId = Auth::id();

        // Query Mágica: Traz apenas Threads Principais (parent_id = null)
        // E só traz se is_shadowbanned for falso, EXCETO se o comentário pertencer ao usuário logado!
        // Também ignora os explicitamente ocultos pela moderação manual.
        $comments = Comment::with(['user', 'replies' => function ($query) use ($currentUserId) {
                $query->where('is_hidden', false)
                      ->where(function ($q) use ($currentUserId) {
                          $q->where('is_shadowbanned', false)
                            ->orWhere('user_id', $currentUserId);
                      });
            }, 'replies.user'])
            ->where('news_id', $this->newsId)
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->where(function ($query) use ($currentUserId) {
                $query->where('is_shadowbanned', false)
                      ->orWhere('user_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.frontend.news-comments', [
            'comments' => $comments
        ]);
    }
}

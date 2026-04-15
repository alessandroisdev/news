import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Suporte para configuração do Sanctum SPA Auth
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-WP-Nonce'] = window.ezcache.rest_nonce;

const rest_url = window.ezcache.rest_url;

const url = (endpoint, method = 'GET') => {
    endpoint = endpoint.indexOf('/') === 0 ? endpoint.substr(1) : endpoint;
    let url = rest_url + 'ezcache/v1/' + endpoint;

    if (['POST', 'GET'].includes(method.toUpperCase())) {
        return url;
    }

    url += rest_url.indexOf('?') > -1 ? '&' : '?';

    return url + '_method=' + method.toUpperCase();
};

const API = {
    get: function (endpoint, data) {
        return axios.get(url(endpoint), data);
    },
    post: function (endpoint, data) {
        return axios.post(url(endpoint, 'POST'), data);
    },
    put: function (endpoint, data) {
        return axios.post(url(endpoint, 'PUT'), data);
    },
    patch: function (endpoint, data) {
        return axios.post(url(endpoint, 'PATCH'), data);
    },
    delete: function (endpoint, data) {
        return axios.post(url(endpoint, 'DELETE'), data);
    }
};

export default API;

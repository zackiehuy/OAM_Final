import axios from "axios";

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    return Promise.reject(error);
});

export const APIConstants = Object.freeze({
    user: "/api/users",
    request: "/api/requests",
    location:  "/api/locations",
    category: "/api/categories",
    asset: "/api/assets",
    assign: "/api/assignments",
    baseQueryURL: function(column,sort,limit,page){
        return `?column=${column}&sort=${sort}&limit=${limit}&page=${page}`;
    },
    get: async function (url) {
        let data;
        await axios.get(url).then( res =>{
            data = res.data;
        }).catch(error => {
            data = error;
        });
        return data;
    },
    delete: async function(url){
        let data;
        await axios.delete(url).then((res) => {
            data = 1;
        }).catch((error) => {
            data = error;
        })
        return data;
    },
    edit: async function(url, dataUpdate){
        let data;
        await axios.put(url, dataUpdate ).then((res) => {
            data = res.data;
        })
        return data;
    },
    create: async function(url, dataCreate){
        let data;
        await axios.post(url, dataCreate).then((res) => {
            data = res.data;
        })
        return data;
    },
    post: async function(url, data){
        return await axios.post(url, data)
    },
    baseDelete: async function(url){
        return await axios.delete(url);
    }
});


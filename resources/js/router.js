
import Vue from 'vue';
import Router from 'vue-router';
import RequestCreateForm from "./components/Request/RequestCreateForm";
import Login from "./Pages/Login";
import ListAdminRequestTable from "./components/Request/ListAdminRequestTable";
import ListReturnRequestTable from "./components/Request/ListReturnRequestTable";
import ListAssignRequestTable from "./components/Request/ListAssignRequestTable";
import ListAssetTable from  "./components/Asset/ListAssetTable";
import AssetCreateForm from  "./components/Asset/AssetCreateForm";
import AssetUpdateForm from  "./components/Asset/AssetUpdateForm";
import ListUserTable from "./components/User/ListUserTable";
import CreateUserForm from "./components/User/CreateUserForm";
import DetailUserForm from "./components/User/DetailUserForm";
import AssetHistory from "./Pages/Asset/AssetHistory";
import axios from "axios";
import {store} from './Store/store';
import ListAssignmentTable from "./components/Assignment/ListAssignmentTable";
import AssignmentCreateForm from "./components/Assignment/AssignmentCreateForm";

Vue.use(Router);
const ifAuthenticated = (to, from, next) => {
    axios.get('/api/authenticated').then( async () => {
        await axios.get('/api/collections',{}).then(response => {
            Vue.prototype.$collections = response.data;
        });
        next()
    }).catch(() => {
        return next('/login')
    })
}

const router = new Router({
    mode: 'history',
    routes: [
    {
        name:'login',
        path: '/login',
        component: Login,

    },
        {
            path: '/assignments',
            component: ListAssignmentTable,
            beforeEnter: ifAuthenticated

    },
        {
            path: '/assignments/create',
            component: AssignmentCreateForm,
            beforeEnter: ifAuthenticated

    },

        {
            path: '/',
            component: ListAdminRequestTable,
            beforeEnter: ifAuthenticated

    },
        {
            path: '/request/form',
            component: RequestCreateForm,
            beforeEnter: ifAuthenticated,
            meta: {is_staff: true}
    },
        {
            path: '/request',
            component: ListAdminRequestTable,
            beforeEnter: ifAuthenticated
    },
        {
            path: '/request/return-request',
            name: 'returnrequest',
            component: ListReturnRequestTable,
            beforeEnter: ifAuthenticated
    },
        {
            path: '/request/assign-request',
            component: ListAssignRequestTable,
            beforeEnter: ifAuthenticated
    },
        {
            path: '/assets',
            component: ListAssetTable,
            beforeEnter: ifAuthenticated
    }, {
        path: '/assets/create',
        name: 'create_asset',
        component: AssetCreateForm,
        beforeEnter: ifAuthenticated
    }, {
        path: '/assets/:id/edit',
        component: AssetUpdateForm,
        name: 'edit_asset',
        beforeEnter: ifAuthenticated
    },{
        path: '/users',
        component: ListUserTable,
        name: 'listUser',
        beforeEnter: ifAuthenticated
    },
        {
            path: '/users/create',
            component: CreateUserForm,
            beforeEnter: ifAuthenticated
    },{
        path: '/users/:id',
        component: DetailUserForm,
        name: 'ShowUsers',
        beforeEnter: ifAuthenticated
    },{
        path: '/assets/:id/history',
            name: 'ListAssetHistory',
        component: AssetHistory,
    }]
})

export default router

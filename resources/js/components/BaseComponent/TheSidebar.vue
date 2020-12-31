<template>
<div>
<!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" v-if="LogginUser !== null">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link to="/" class="brand-link">
        <span class="brand-text font-weight-light">OAM</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Hello {{getUser.name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                <li class="nav-item">
                        <router-link to="/request" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Request</p>
                        </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/assignments" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Assigment List
                        </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/assets" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Asset List</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/users" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>User List</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" v-on:click.prevent="logOut">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            LOG OUT
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
</div>
</template>

<script>
import axios from 'axios';
export default {

    name: "TheSidebar",
    computed:{
        getUser(){
            return this.$store.getters.getUser
        }
    },
    methods: {
        logOut(){
            axios.delete('/api/logout').then(r=>{
                    this.$store.commit("setUser", {});
                    this.$router.push('/login');
            })
        }
    }
}
</script>

<style scoped>

</style>

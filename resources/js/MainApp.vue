<template>
<div>
    <!-- Main Sidebar Container -->
    <the-sidebar v-if="LogginUser >= 0"></the-sidebar>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <router-view></router-view>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <the-footer></the-footer>
    <!-- Main Footer -->

</div>
</template>

<script>
import TheSidebar from "./components/BaseComponent/TheSidebar";
import TheFooter from "./components/BaseComponent/TheFooter";
import Login from "./Pages/Login"
import Vue from "vue";

export default {
    name: "MainApp",
    components: {
        TheSidebar,
        TheFooter
    },
    computed: {
        LogginUser() {
            return  this.$store.getters.getUser.is_admin;
        }
    },
}
</script>

<style scoped>

</style>

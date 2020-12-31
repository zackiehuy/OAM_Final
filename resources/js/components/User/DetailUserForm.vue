<template >
    <div class="container">
        <div class="row">
            <!-- tab -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="col-sm-offset-2 row">
                                <h3 class="row ml-1 ">Detail User</h3>
                                <router-link to="/users" class = "ml-auto mr-3" v-if="this.$store.getters.getUser.is_admin === 1">
                                    <button class="btn btn-success ">Back to list</button>
                                </router-link>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal" v-on:submit.prevent="editUser(user.id)">
                                    <div class="form-group" style="background-color: #FFB6C1" v-if="this.errors !== null">
                                        <div v-for="error in errors" :key="error.id">
                                            <div>{{error.toString()}}</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Staff Code</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.staff_code" type="text" class="form-control" id="inputStaffCode"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.name" type="text" class="form-control" id="inputName"placeholder="Name"
                                            v-bind:class="listError.includes('name') ? 'is-invalid' : ''"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label" readonly>Email</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.email" type="email" class="form-control" id="inputEmail" placeholder="Email"
                                            v-bind:class="listError.includes('email') ? 'is-invalid' : ''" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDateOfBirth" class="col-sm-2 control-label">Date of birth</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.date_of_birth "  type="date" class="form-control" id="inputDateOfBirth"
                                            v-bind:class="listError.includes('date_of_birth') ? 'is-invalid' : ''"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputJoinedDate" class="col-sm-2 control-label">Joined Date</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.joined_date" type="date" class="form-control" id="inputJoinedDate"
                                            v-bind:class="listError.includes('joined_date') ? 'is-invalid' : ''"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Gender</label>

                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="select2-purple">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" id="gender1" name="portalSelect" v-model="user.gender" value="0"
                                                        v-bind:class="listError.includes('gender') ? 'is-invalid' : ''">
                                                        <label class="form-check-label" for="gender1">Male</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" v-model="user.gender" type="radio" name="gender" id="gender2" value="1"
                                                        v-bind:class="listError.includes('gender') ? 'is-invalid' : ''">
                                                        <label class="form-check-label" for="gender2">Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Type</label>

                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="select2-purple">
                                                    <select class="form-control select2" v-model="user.is_admin"
                                                            style="width: 100%;" name="gender">
                                                        <option value="1">Admin</option>
                                                        <option value="0">Staff</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Location</label>

                                        <div class="col-sm-12">
                                            <select class="form-control select2" name="location"  v-model="user.location_id">
                                                <option selected v-bind:value="user.location_id">{{userLocation}}</option>
                                                <option v-for="location in locations" :key="location.id"
                                                        v-bind:value="location.id"
                                                        v-if="location.id !== user.location_id">{{ location.name }}</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group text-center">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success" >Edit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- end tabs -->
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {APIConstants} from "../../constants";
import Vue from "vue";

export default {
    data(){
        return {
            user: {},
            locations: [],
            form: {},
            errors: {},
            listError: [],
            userLocation: null,
        }
    },
    methods: {
        async getUserById() {
            if(this.$store.getters.getUser.is_admin === 0){
                if(this.$route.params.id != this.$store.getters.getUser.id)
                {
                    alert("You don't have this permission");
                    this.$router.push(`/users/${this.$store.getters.getUser.id}`);
                }
            }
            this.user = await APIConstants.get(APIConstants.user + '/' + this.$route.params.id);
            if(this.user[0] == '404')
            {
                swal.fire({
                    title: this.user[1],
                }).then((result)=> {
                    if(result.isConfirmed){
                        this.$router.push('/users/');
                    }
                });
            }
            this.locations = Vue.prototype.$collections.locations;
            this.userLocation = this.user.user_location.name;
        },
        editUser(){
            swal.fire({
                title: 'Do you want to edit this user?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then(async(result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const confirmEdit = await APIConstants.edit(APIConstants.user + '/' + this.$route.params.id, this.user).catch((error) => {
                        this.errors = error.response.data.errors;
                        this.listError= Object.keys(error.response.data.errors);
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Edit failed!',
                            footer: '<a href="/users">Back to list</a>'
                        })
                    });
                    if(confirmEdit.id == undefined)
                    {
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Edit failed!',
                            footer: '<a href="/users">Back to list</a>'
                        })
                    }
                    else
                    {
                        swal.fire({
                            title: 'Edit successfully!',
                        });
                        this.$router.push('/users');
                    }
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
        }
    },
    created() {
        this.getUserById();
    }
};
</script>

<template>
    <div class="container">
        <div class="row">
            <!-- tab -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-sm-offset-2 row">
                        <h3 class="row ml-1 ">Create Assignment</h3>
                        <router-link to="/assignments/" class = "ml-auto mr-3">
                            <button class="btn btn-success "> <i class="fa fa-reply" aria-hidden="true"></i> Back to list</button>
                        </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal" v-on:submit.prevent="createAssignment">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">User</label>
                                        <input list="user" type="text" class="form-control" v-model="assignment.user_id" v-on:keyup="changeUser($event)">
                                        <datalist id = "user">
                                            <option v-for="user in users" :key="user.id" :value="user.id">
                                                {{user.staff_code}} ( {{user.name}} )
                                            </option>
                                        </datalist>
                                        <a href="" v-on:click.prevent="detailUserButton(changeUserId)">Detail</a>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Asset</label>
                                        <input list="asset" type="text" class="form-control" v-model="assignment.asset_id" v-on:keyup="changeAsset($event)">
                                        <datalist id="asset">
                                            <option v-for="asset in assets" :key ="asset.id" :value="asset.id" >
                                                {{asset.asset_code}} ( {{asset.name}} )
                                            </option>
                                        </datalist>
                                        <a href="" v-on:click.prevent="detailAssetButton(changeAssetId)">Detail</a>
                                    </div>
                                    <div class="form-group text-center">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success">Submit</button>
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
        <div class="modal fade" id="modalAsset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Asset details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">Asset Name</label>
                            <p v-if="detailAsset">{{ detailAsset.name }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Asset Code:</label>
                            <p v-if="detailAsset">{{ detailAsset.asset_code}}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Specs:</label>
                            <div v-if="detailAsset">
                                <p v-if="detailAsset.asset_detail" v-for="spec in detailAsset.asset_detail">{{spec.value}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">User details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">UserName:</label>
                            <p v-if="detailUser">{{ detailUser.name }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Staff Code:</label>
                            <p v-if="detailUser">{{ detailUser.staff_code}}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Email:</label>
                            <p v-if="detailUser">{{ detailUser.email}}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Date of birth:</label>
                            <p v-if="detailUser">{{ detailUser.date_of_birth}}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Join date:</label>
                            <p v-if="detailUser">{{ detailUser.joined_date}}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Gender:</label>
                            <div v-if="detailUser">
                                <p v-if="detailUser.gender == 0">Male</p>
                                <p v-else>Female</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Type:</label>
                            <div v-if="detailUser">
                                <p v-if="detailUser.is_admin == 1">Admin</p>
                                <p v-else>Staff</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Location:</label>
                            <p v-if="detailUser">{{ detailUser.location_name}}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {APIConstants} from "../../constants";
import axios from "axios";
export default {
    data() {
        return {
            assignment: {
                user_id: '',
                asset_id: '',
                is_assigned: 0,
            },
            assets: [],
            users: [],
            detailAsset: [],
            detailUser: [],
            changeAssetId: [],
            changeUserId: [],
        }
    },
    methods: {
        async createAssignment() {
            this.assignment.is_assigned = 0;
            swal.fire({
                title: 'Do you want to return this assignment?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then(async(result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('#modalCreate').modal('hide');
                    if(this.assignment.user_id === '' || this.assignment.asset_id === '')
                    {
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'You need to choose both this asset and user to create assignment!',
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                        this.fetchAssignment();
                    }
                    else
                    {
                        const data = await APIConstants.create(`${APIConstants.assign}`,this.assignment);
                        if(data === 'This asset is belongs to another user , cannot create assignment')
                        {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'This asset is belongs to another user , cannot create assignment!',
                                footer: '<a href>Why do I have this issue?</a>'
                            })
                            this.fetchAssignment();
                        }
                        else
                        {
                            swal.fire(
                                'Create!',
                                'Create assignment success.',
                                'success'
                            )
                            this.$router.push('/assignments');
                        }
                    }
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
        },
        async fetchAssignment() {
            const data = await APIConstants.get(`${APIConstants.assign}/create`);
            this.assets = data.assets;
            this.users = data.users;
        },
        detailAssetButton(asset) {
            this.detailAsset = asset;
            $('#modalAsset').modal('show');
        },
        detailUserButton(user) {
            this.detailUser = user;
            $('#modalUser').modal('show');
        },
        changeAsset(asset){
            let value = asset.target.value;
            let display = this.assets.find(d => d.id == value);
            this.changeAssetId = display;
        },
        changeUser(user){
            let value = user.target.value;
            let display = this.users.find(d => d.id == value);
            this.changeUserId = display;
            if(display)
            {
                this.changeUserId['location_name'] = display.user_location.name;
            }
        }
    },
    created() {
        this.fetchAssignment();
    }
}
</script>

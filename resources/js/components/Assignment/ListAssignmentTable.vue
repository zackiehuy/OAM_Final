<template>
        <div class="container">
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Assignment Table</h3>

                            <div class="card-tools">
                                <router-link to= "/assignments/create" tag="button" class="btn btn-success" v-if="this.$store.getters.getUser.is_admin === 1">
                                    Add New <i class="fas fa-user-plus fa-fw"></i>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-header container">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Staff</label>
                                    <input list="user" type="text" class="form-control" v-model="search.UserId" v-on:keyup="getListAssignments">
                                    <datalist id="user">
                                        <option v-bind:value="user.id"
                                            v-for="user of getUser" :key="user.id">
                                            StaffCode: {{user.staff_code}}, Username: {{user.name}}
                                        </option>
                                    </datalist>
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Asset</label>
                                    <input list="asset" type="text" class="form-control" v-model="search.AssetId" v-on:keyup="getListAssignments">
                                    <datalist id="asset">
                                        <option v-bind:value="asset.id"
                                                v-for="asset of getAsset" :key="asset.id">
                                            AssetCode: {{asset.asset_code}}, AssetName: {{asset.name}}
                                        </option>
                                    </datalist>
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Started Date</label>
                                    <input class="form-control" type="date" v-model="search.StartedDate" v-on:change="getListAssignments">
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >End Date</label>
                                    <input type="date" class="form-control" v-model="search.EndDate" v-on:change="getListAssignments">
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Status</label>
                                    <select class ="form-control" v-model="search.Status"
                                            name = "assignment" v-on:change="getListAssignments">
                                        <option></option>
                                        <option v-bind:value="status.status_id"
                                                v-for="status in getStatus" :key="status.status_id">
                                            {{status.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Create By</label>
                                    <input list="createBy" class="form-control" v-model="search.CreateBy" v-on:keyup="getListAssignments">
                                    <datalist id="createBy">
                                        <option v-bind:value="createBy.id"
                                                v-for="createBy of getCreateBy" :key="createBy.id">
                                            StaffCode: {{createBy.staff_code}}, Username: {{createBy.name}}
                                        </option>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th @click="getSort('Id')">Id</th>
                                    <th @click="getSort('User')">User Id</th>
                                    <th @click="getSort('Asset')">Asset Id</th>
                                    <th @click="getSort('StartedDate')">Start Date</th>
                                    <th @click="getSort('EndDate')">End Date</th>
                                    <th @click="getSort('Status')">Status</th>
                                    <th @click="getSort('CreateBy')">Create By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="assignment in list_assignments.data" :key="assignment.id">
                                    <td>{{assignment.id}}</td>
                                    <td>{{assignment.user.name}}</td>
                                    <td>{{assignment.asset.name}}</td>
                                    <td>{{assignment.started_date}}</td>
                                    <td>{{assignment.end_date}}</td>
                                    <td v-if="assignment.status_id == 1 && assignment.is_assigned == 0"><span class="badge badge-warning">Waiting</span></td>
                                    <td v-else-if="assignment.status_id == 2"><span class="badge badge-success">Assigned</span></td>
                                    <td v-else-if="assignment.status_id == 3"><span class="badge badge-danger">Denied</span></td>
                                    <td v-else-if="assignment.status_id == 4"><span class="badge badge-dark">Returned</span></td>
                                    <td v-else-if="assignment.status_id == 1 && assignment.is_assigned == 1"><span class="badge badge-info ">Waiting to Return</span></td>
                                    <td v-if="assignment.create_by == null"></td>
                                    <td v-else>{{assignment.create_by.name}}</td>
                                    <td v-if="assignment.status_id == 2 "><button id="returnbutton" class="btn btn-primary btn-sm" @click="updateReturnStatusAssignment(assignment.user_id,assignment.id)">Return</button></td>
                                    <td v-if="assignment.status_id == 1">
                                        <div v-if="assignment.is_assigned === 0">
                                            <div v-if="$store.getters.getUser.is_admin === 0">
                                                <a style="color:green;" href="#" v-on:click="changeStatus(assignment.id, 2)"><strong>Accept</strong></a> |
                                                <a style="color:red;" href="#" v-on:click="changeStatus(assignment.id, 3)"><strong>Decline</strong></a>
                                            </div>
                                            <div v-else>
                                                <button id="deletebutton" class="btn btn-danger btn-sm" @click="deleteAssignment(assignment.id)">Delete</button>
                                            </div>
                                        </div>
                                        <div v-else>
                                                <button id="" class="btn btn-primary btn-sm" disabled>Return</button>
                                        </div>
                                    </td>
                                    <td v-else></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <strong> Per Page: </strong>
                                        <select v-on:change="getLimit($event)">
                                            <option value="10" selected>10</option>
                                            <option value="5">5</option>
                                            <option value="3">3</option>
                                            <option value="1">1</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
<!--                                    <base-pagination v-bind:pagination="pagination" v-on:click.native="getListAssignments(pagination.current_page, column, sort)"></base-pagination>-->
                                    <base-pagination v-bind:pagination="list_assignments" v-bind:pages="pages" v-on:click.native="getListAssignments"></base-pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

            </div>

        </div>

</template>

<script>

import axios from "axios";
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";

export default {
    components: {BasePagination},
    data(){
        return{
            list_assignments: {
                current_page: 1,
                request: {
                    create_by : null,
                }
            },
            // pagination: {
            //     total: 0,
            //     per_page: 0,
            //     from: 1,
            //     to: 0,
            //     current_page: 1
            // },
            column: 'StartedDate',
            sort: 'desc',
            limit: 10,
            // page: '1',
            UserId_Field: [],
            AssetId_Field: [],
            StartDate_Field: [],
            EndDate_Field: [],
            Status_Field: [],
            Create_Field: [],
            getUser: [],
            getAsset: [],
            getCreateBy: [],
            getStatus: [
                {
                    status_id : 1 ,
                    name : 'Waiting',
                },
                {
                    status_id : 2 ,
                    name : 'Accept',
                },
                {
                    status_id : 3 ,
                    name : 'Denided',
                },
                {
                    status_id : 4 ,
                    name : 'Returned',
                },
                {
                    status_id : 5 ,
                    name : 'Waiting to return',
                }
            ],
            search: {},
            oldSearch: {},
            statuses: [
                {
                    id: 1,
                    name: 'Waiting',
                    value: 1
                },
                {
                    id: 2   ,
                    name: 'Returned',
                    value: 0
                },
                {
                    id: 3   ,
                    name: 'Assigned',
                    value: 0
                }
            ],
            pages: [
                {
                    id: 1,
                    name: 1,
                    value: 1
                },
                {
                    id: 2   ,
                    name: 5,
                    value: 5
                }
            ]

        }
    },
    created(){
        this.getListAssignments();

    },
    methods: {
        async getSearch(){
            let url = this.getQuery();
            this.list_assignments = await APIConstants.get(url);
        },

        getLimit(event){
            this.limit = event.target.value;
            this.list_assignments.current_page =1;
            this.getListAssignments();
        },
        getQuery(){
            let removeSearch = [];
            $.each(this.search, function(key , value) {
                if(value === "")
                {
                    removeSearch.push(key);
                }
            })
            if(removeSearch)
            {
                let remove;
                for(remove of removeSearch)
                {
                    delete this.search[remove];
                }
                removeSearch = [];
            }
            let url = APIConstants.assign + APIConstants.baseQueryURL(this.column,this.sort,this.limit,this.list_assignments.current_page);
            $.each(this.search, function(key , value) {
                if(value !== '')
                {
                    url += `&${key}=${value}`;
                }
            })
            return url;
        },
        async returnRequest(userid,assignmentid){
            await APIConstants.post(APIConstants.request,{user_id: userid, assignment_id: assignmentid, is_returned:  1,created_by: this.$store.getters.getUser.id}).then(res => {
                if(res.data === 0)
                {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'This assignment have a request return already!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                }
                else
                {
                    swal.fire('Return request Success!', '', 'success')
                }
            }).catch(error => {

            });
            this.getListAssignments();
        },
        async updateReturnStatusAssignment(userid,assignmentid){
            swal.fire({
                title: 'Do you want to return this assignment?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then(async(result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const data = await APIConstants.edit(APIConstants.assign+"/"+assignmentid,{status_id: 1, is_assigned : 0});
                    if(data !== 0)
                    {
                        this.returnRequest(userid,assignmentid);
                    }
                    else
                    {
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'This assignment have a request return already!',
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                        this.getListAssignments();
                    }
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
            this.$forceUpdate();
        },
        async getListAssignments() {
            let url = this.getQuery();
            const list = await APIConstants.get(url);
            this.list_assignments = list;
            this.getAllAssignments(this.list_assignments.total);
        },
        async getAllAssignments(total) {
            const url = APIConstants.assign + APIConstants.baseQueryURL(this.column,this.sort,total,1)
          const list = await APIConstants.get(url);
            let assignments;
            for(assignments of list.data)
            {
                if(assignments.user)
                {
                    if(this.getUser.length === 0)
                    {
                        this.getUser.push(assignments.user);
                    }
                    else
                    {
                        if(this.getUser.findIndex(i => i.id === assignments.user.id) === -1)
                        {
                            this.getUser.push(assignments.user)
                        }
                    }
                }
                if(assignments.asset)
                {
                    if(this.getAsset.length === 0)
                    {
                        this.getAsset.push(assignments.asset);
                    }
                    else
                    {
                        if(this.getAsset.findIndex(i => i.id === assignments.asset.id) === -1)
                        {
                            this.getAsset.push(assignments.asset)
                        }
                    }
                }
                if(assignments.create_by)
                {
                    if(this.getCreateBy.length === 0)
                    {
                        this.getCreateBy.push(assignments.create_by);
                    }
                    else
                    {
                        if(this.getCreateBy.findIndex(i => i.id === assignments.create_by.id) === -1)
                        {
                            this.getCreateBy.push(assignments.create_by)
                        }
                    }
                }
            }
        },
        async changeStatus(assignment_id, status){
            swal.fire({
                title: 'Do you want to response assignment?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then(async(result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const data = await APIConstants.edit(`${APIConstants.assign}/${assignment_id}`,{status_id: status , new_asset : 1});
                    if(data === 0)
                    {
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'This assignment have a response already!',
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                    }
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
            this.getListAssignments();
            this.$forceUpdate();
        },
        async changeStatusReturn(user_id,assignment_id, status_id)
        {
            const data = await APIConstants.edit(`${APIConstants.assign}/${assignment_id}`,{status_id: status_id, is_assigned : 1});
            if(data !== 0)
            {
                const request = await APIConstants.get(`${APIConstants.request}/}`)
            }
            else
            {
                alert("This assignment have a request return already");
                this.getListAssignments();
            }
            this.$forceUpdate();
        },
        deleteAssignment(assignment_id){
            swal.fire({
                title: 'Do you want to delete this assignment?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then(async(result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    await APIConstants.baseDelete(`${APIConstants.assign}/${assignment_id}`).then(res => {
                        if(res.data == 0)
                        {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'You can\'t delete this assignment because this assignment is assigned!',
                                footer: '<a href>Why do I have this issue?</a>'
                            })
                        }
                        else
                        {
                            swal.fire('Delete Success!', '', 'success')
                        }
                        this.getListAssignments();
                    }).catch(e => {
                        console.log(e);
                    });
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
        },
    getSort(column){
        if (this.sort === 'asc'){
            this.sort = 'desc';
        }else{
            this.sort = 'asc';
        }
        this.column = column;
        return this.getListAssignments();
    },
        mounted() {
            this.getListAssignments();
        },
    },
}

</script>
<style lang="css" scoped>

</style>

<template>
<div class="container">
    <div class="row mt-5">
        <div class="">
            <div class="card">
                <div class="card-header">
                    <div class="col-sm-offset-2 row">
                        <h3 class="row ml-1 ">Request list</h3>
                        <router-link to="/request/form" class = "ml-auto mr-3" v-if="this.$store.getters.getUser.is_admin === 0">
                            <button class="btn btn-success ">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                        </router-link>
                    </div>
                </div>
                <div class="card-header container">
                    <div class="row">
                        <h3 class="card-title">Filter</h3>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label>Staff Name</label>
                            <input list="user-id" class="form-control" type="text" placeholder="Search" aria-label="Search"
                                   v-model="search.user_id" v-on:keyup="searchRequests">
                            <datalist id="user-id">
                                <option></option>
                                <option v-bind:value="user.id"
                                        v-for="user in getUser" :key="user.id">
                                    StaffCode: {{user.staff_code}}, Username: {{user.name}}
                                </option>
                            </datalist>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Assignment Id</label>
                            <input list="my-list-id" v-model="search.assignment_id" v-on:keyup="searchRequests" type="text" class="form-control">
                            <datalist id="my-list-id">
                                <option></option>
                                <option v-bind:value="assignment.id"
                                        v-for="assignment in getAssignment" :key="assignment.id">
                                    AssetCode: {{assignment.asset.asset_code}}, AssetName: {{assignment.asset.name}}
                                </option>
                            </datalist>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Category</label>
                            <select class ="form-control" v-model="search.category_id"
                                    name = "assignment" v-on:change="searchRequests">
                                <option value = ""></option>
                                <option v-bind:value="category.id"
                                        v-for="category in categories" :key="category.id">
                                    {{category.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Requested Date</label>
                            <input  class="form-control" type="date" placeholder="Search" aria-label="Search"
                                   v-model="search.requested_date" v-on:change="searchRequests">
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Response Date</label>
                            <input  class="form-control" type="date" placeholder="Search" aria-label="Search"
                                   v-model="search.response_date" v-on:change="searchRequests">
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Create By</label>
                            <input list="create-by-id" class="form-control" type="text" placeholder="Search" aria-label="Search"
                                   v-model="search.created_by" v-on:keyup="searchRequests">
                            <datalist id="create-by-id">
                                <option v-bind:value="createBy.id"
                                        v-for="createBy in getCreateBy" :key="createBy.id">
                                    AssetCode: {{createBy.staff_code}}, AssetName: {{createBy.name}}
                                </option>
                            </datalist>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Type Request</label>
                            <select class ="form-control" v-model="search.return"
                                    name = "assignment" v-on:change="searchRequests">
                                <option></option>
                                <option value = "0">New Assignment</option>
                                <option value = "1">Return</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>Status</label>
                            <select class ="form-control" v-model="search.status"
                                    name = "status" v-on:change="searchRequests">
                                <option value = ""></option>
                                <option v-bind:value="status.id"
                                        v-for="status in statuses" :key="status.id">
                                    {{status.name}}
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    No.
                                </th>
                                <th v-on:click="getSort('User')">
                                    Staff Name
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('Assignment')">
                                    Assignment
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetCategory')">
                                    Category
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('RequestDate')">
                                    Requested Date
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('ResponseDate')">
                                    Response Date
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('CreatedBy')">
                                    Create By
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('Assignment')">
                                    Return
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('Status')">
                                    Status
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(request,index) of list_requests.data" :key="request.id">
                                <td>{{index+1}}</td>
                                <td>{{ request.user.name}}</td>
                                <td>{{request.assignment_id}}</td>
                                <td v-if="request.category">
                                    {{request.category.name}}
                                </td>
                                <td v-else>

                                </td>
                                <td>{{ request.requested_date | formatDateTime}}</td>
                                <td>{{ request.response_date | formatDateTime}}</td>
                                <td  v-if="request.create_by != null">
                                    {{request.create_by.name}}
                                </td>
                                <td  v-else>
                                </td>
                                <td  v-if="request.assignment_id != null">
                                    <strong style="color:green;">Returned
                                    </strong>
                                </td>
                                <td  v-else>
                                </td>
                                <td v-if="request.status === 2">
                                    <strong style="color:green;">Accepted</strong>
                                </td>
                                <td v-else-if="request.status === 3">
                                    <strong style="color:red;">Declined</strong>
                                </td>
                                <td v-else-if="request.status === 1">
                                    <div v-if="request.assignment_id === null">
                                        <div v-if="checkRoles === 1">
                                            <a style="color:green;" href="#" v-on:click="checkAsset(request,request.asset_category_id,request.create_by.id)"><strong>Accept</strong></a> |
                                            <a style="color:red;" href="#" v-on:click="changeStatus(0,request.id, 3)"><strong>Decline</strong></a>
                                        </div>
                                        <div v-else>
                                            <strong style="color:blue;">Waiting</strong>
                                        </div>
                                    </div>
                                    <div v-if="request.assignment_id !== null">
                                        <div v-if="request.create_by.is_admin === 1 && checkRoles === 0 ||
                                                    request.create_by.is_admin === 0 && checkRoles === 1">
                                            <a style="color:green;" href="#" v-on:click="changeStatus(request.assignment_id,request.id, 2)"><strong>Accept</strong></a> |
                                            <a style="color:red;" href="#" v-on:click="changeStatus(request.assignment_id,request.id, 3)"><strong>Decline</strong></a>
                                        </div>
                                        <div v-else>
                                            <strong style="color:blue;">Waiting</strong>
                                        </div>
                                    </div>
                                </td>
                                <td v-else-if="request.status === 4">
                                    <strong style="color:red;">Expired</strong>
                                </td>
                                <td v-if="request.created_by === $store.getters.getUser.id && request.status === 1">
                                    <div v-if="request.assignment_id === null">
                                        <div v-if="checkRoles === 1">
                                        </div>
                                        <div v-else>
                                            <button class="btn btn-danger btn-sm" @click="deleteRequest(request.id)">Delete</button>
                                        </div>
                                    </div>
                                    <div v-if="request.assignment_id !== null">
                                        <div v-if="request.create_by.is_admin === 1 && checkRoles === 0 ||
                                                    request.create_by.is_admin === 0 && checkRoles === 1">
                                        </div>
                                        <div v-else>
                                            <button class="btn btn-danger btn-sm" @click="deleteRequest(request.id)">Delete</button>
                                        </div>
                                    </div>
                                </td>
                                <td v-else>

                                </td>
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
                                <base-pagination v-bind:pagination="list_requests" @click.native="getListRequests"></base-pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Asset details</h5>
                        <select v-if="assignment.length !== 0" v-model="form.asset_id" class ="form-control" v-on:change="changeAssetCode($event)" >
                            <option v-for="asset in list_assets" :value="asset.id" :key="asset.id">
                                {{asset.name}} ( {{asset.asset_code}} )
                            </option>
                        </select>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if = "assignment.asset != null">
                        <div class="form-group">
                            <label class="col-form-label">Asset Name</label>
                            <p>{{ this.assignment.asset.name }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Asset Code:</label>
                            <p>{{ this.assignment.asset.asset_code }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Asset Location:</label>
                            <p>{{this.assetlocation}}</p>
                        </div>
                        <div class="form-group">
                            <label  class="col-form-label">Specs:</label>
                            <div v-if="this.assignment.asset.asset_detail">
                                <p v-for="spec in this.assignment.asset.asset_detail">{{spec.value}}</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-on:click="createAssignment" class="btn btn-success">Create Assignment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination"
import Vue from "vue";
export default {
    name: "ListAdminRequestTable",
    components:{
        BasePagination
    },
    data(){
        return{
            list_requests: {
                current_page: 1
            },
            column: 'Status',
            sort: 'asc',
            limit: 10,
            search:{},
            categories:[],
            statuses:[],
            list_assets:[],
            assignment:{},
            form:{},
            assetlocation: {},
            request: {},
            getAssignment: [],
            getRequest: {},
            getUser: [],
            getCreateBy: [],
            getRequestDate: [],
            getResponseDate: [],
        }
    },
    created() {
        this.getListRequests();
    },
    computed: {
        checkRoles(){
           return  this.$store.getters.getUser.is_admin;
        }
    },
    methods: {
        async searchRequests() {
            this.getListRequests();
        },
        getUrl(){
            let query = APIConstants.baseQueryURL(this.column,this.sort,this.limit,this.list_requests.current_page);
            $.each(this.search, function (key, value) {
                if (value !== '') {
                    query += '&' + key + '=' + value;
                } else {
                }
            })
            return query;
        },
        async getListRequests() {
            let url = this.getUrl()
            this.list_requests = await APIConstants.get(`${APIConstants.request}${url}`).catch((error) => {
                this.errors = error.response.data.errors.name;
            });
            this.categories = Vue.prototype.$collections.asset_categories;
            this.statuses = Vue.prototype.$collections.statuses;
            this.getFilter();
        },
        async getFilter(){
            let url = APIConstants.baseQueryURL(this.column,this.sort,this.list_requests.total,this.list_requests.current_page)
            this.getRequest = await APIConstants.get(`${APIConstants.request}${url}`).catch((error) => {
                this.errors = error.response.data.errors.name;
            });
            let request;
            for(request of this.getRequest.data)
            {
                if(request.assignment)
                {
                    if(this.getAssignment.length === 0)
                    {
                        this.getAssignment.push(request.assignment);
                    }
                    else
                    {
                        if(this.getAssignment.findIndex(i => i.id === request.assignment.id) === -1)
                        {
                            this.getAssignment.push(request.assignment);
                        }
                    }
                }
                if(request.user)
                {
                    if(this.getUser.length === 0)
                    {
                        this.getUser.push(request.user);
                    }
                    else
                    {
                        if(this.getUser.findIndex(i => i.id === request.user.id) === -1)
                        {
                            this.getUser.push(request.user);
                        }
                    }
                }
                if(request.create_by)
                {
                    if(this.getCreateBy.length === 0)
                    {
                        this.getCreateBy.push(request.create_by);
                    }
                    else
                    {
                        if(this.getCreateBy.findIndex(i => i.id === request.create_by.id) === -1)
                        {
                            this.getCreateBy.push(request.create_by);
                        }
                    }
                }
            }
        },
        async checkAsset(request,asset_category_id,user_id){
            this.request = request;
            this.list_assets = [];
            this.assignment = {};
            this.form.user_id = {};
            this.form.user_id = user_id;
            this.form.status_id = 2;
            const list = await APIConstants.get(`${APIConstants.assign}/create`);
            for(let keys of list.assets)
            {
                if(keys.asset_category_id === asset_category_id)
                {
                    this.list_assets.push(keys);
                }
            }
            if(this.list_assets.length === 0)
            {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This Asset Category don\'t have any Asset now!',
                    footer: '<a href>Why do I have this issue?</a>'
                })
            }
            else
            {
                $('.modal').modal('show');
            }
        },
        async createAssignment()
        {
            swal.fire({
                title: 'Do you want to save the changes?',
                showDenyButton: true,
                confirmButtonText: `Save`,
                denyButtonText: `Don't save`,
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('.modal').modal('hide');
                    this.form.is_assigned = 1;
                    const data = await APIConstants.create(APIConstants.assign,this.form).catch(error => {
                        console.log(error);
                    });
                    if(data === "This asset is belongs to another user , cannot create assignment")
                    {
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'This asset is belongs to another user , cannot create assignment!',
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                    }
                    else
                    {
                        await APIConstants.edit(`${APIConstants.request}/${this.request.id}`,{status : 2});
                        swal.fire('Create assignment Success!', '', 'success');
                    }
                    this.getListRequests();
                } else if (result.isDenied) {
                    this.getListRequests();
                }
            })
        },
        changeAssetCode(e)
        {
            this.assignment = {};
            this.assignment.asset = {};
            let value = e.target.value;
            let display = this.list_assets.find(d => d.id == value);
            this.assignment.asset = display;
            this.assetlocation = this.assignment.asset.asset_location.name;
        },
        changeStatus(assignment_id,id, statusId) {
            swal.fire({
                title: 'Do you want to save the changes?',
                showDenyButton: true,
                confirmButtonText: `Save`,
                denyButtonText: `Don't save`,
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    await APIConstants.edit(`${APIConstants.request}/${id}`,{status : statusId});
                    if(assignment_id !== 0)
                    {
                        await APIConstants.edit(`${APIConstants.assign}/${assignment_id}`,{status_id : statusId, is_assigned : 1});
                    }
                    swal.fire('Update Success!', '', 'success');
                    this.getListRequests();
                } else if (result.isDenied) {
                    this.getListRequests();
                }
            })
        },
        deleteRequest(request_id)
        {
            swal.fire({
                title: 'Do you want to delete this request?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    await APIConstants.baseDelete(`${APIConstants.request}/${request_id}`).then(res => {
                        if(res.data === 0)
                        {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'This request can\'t delete because that\'s have response!',
                                footer: '<a href>Why do I have this issue?</a>'
                            })
                        }
                        else
                        {
                            swal.fire('Delete Success!', '', 'success');
                        }
                        this.getListRequests();
                    }).catch(e => {
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Delete Fail!',
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                    });
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
        },
        getSort(column) {
            if(this.sort === 'asc'){
                this.sort = 'desc';
            } else if(this.sort === 'desc' ) {
                this.sort = 'asc';
            }
            this.column = column;
            this.getListRequests();
        },
        getLimit(event){
            this.limit = event.target.value;
            this.list_requests.current_page =1;
            this.getListRequests();
        }
    }
}
</script>

<style scoped>

</style>

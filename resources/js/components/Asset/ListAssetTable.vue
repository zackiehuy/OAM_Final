<style scoped>
.dropdown-menu {
    width:150px
}

.dropdown-menu.columns-3 {
    min-width: 500px;
}
.dropdown-menu li a {
    padding: 5px 15px;
    font-weight: 300;
}
.multi-column-dropdown {
    list-style: none;
}
.multi-column-dropdown li a {
    display: block;
    clear: both;
    line-height: 1.428571429;
    color: #333;
    white-space: normal;
}
.multi-column-dropdown li a:hover {
    text-decoration: none;
    color: #262626;
    background-color: #f5f5f5;
}

@media (max-width: 767px) {
    .dropdown-menu.multi-column {
        min-width: 240px !important;
        overflow-x: hidden;
    }
}
</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assets Table</h3>

                        <div class="card-tools" v-if="checkRoles === 1">
                            <router-link to= "/assets/create" tag="button" class="btn btn-success">
                                Add New <i class="fas fa-user-plus fa-fw"></i>
                            </router-link>
                        </div>
                        <div class="card-tools" v-else>
                            <router-link to= "/assets/create" tag="button" class="btn btn-success" v-if="this.$store.getters.getUser.is_admin === 1">
                                Create Asset <i class="fas fa-plus fa-fw"></i>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-header container">
                        <div class="form-row row">
                            <div class="col-sm-3">
                                <label class="mr-sm-2" >Asset Code</label>
                                <input list="asset-code" class="form-control" type="text" v-model="search.asset_code" v-on:keyup="getResults">
                                <datalist id="asset-code">
                                    <option v-bind:value="asset_code"
                                            v-for="asset_code of getAssetCode" :key="asset_code">
                                    </option>
                                </datalist>
                            </div>
                            <div class="col-sm-3">
                                <label class="mr-sm-2" >Asset Name</label>
                                <input list="asset-name" class="form-control" type="text" v-model="search.asset_name" v-on:keyup="getResults">
                                <datalist id="asset-name">
                                    <option v-bind:value="asset_name"
                                            v-for="asset_name of getName" :key="asset_name">
                                    </option>
                                </datalist>
                            </div>
                            <div class="col-sm-3">
                                <label class="mr-sm-2" >Location</label>
                                <select v-model="search.location" v-on:change="getResults" class="form-control">
                                    <option></option>
                                    <option v-bind:value="location.id"
                                            v-for="location of getLocation" :key="location.id">
                                        {{location.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="mr-sm-2" >Asset Category</label>
                                <select v-model="search.asset_category_id" v-on:change="changeCategory($event)" class="form-control">
                                    <option></option>
                                    <option v-bind:value="category.id"
                                            v-for="category of getCategory" :key="category.id">
                                            {{category.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-3" v-for="spec of specification" v-if="specification">
                                    <label class="mr-sm-2" >{{spec.name}}</label>
                                    <input :list=spec.name type="text" class="form-control" v-model="specifications[spec.specification_id]" v-on:keyup="getResults">
                                    <datalist :id=spec.name>
                                        <option v-bind:value="val.value"
                                                v-for="val of spec.value" :key="val.value">
                                        </option>
                                    </datalist>
                            </div>
                            <div class="col-sm-3">
                                <label class="mr-sm-2">Installed Date</label>
                                <div class="mr-sm-2">
                                    <input type="date" class="form-control"
                                           v-model="search.installed_date" v-on:change="getResults">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="mr-sm-2" >Status</label>
                                <select v-model="search.status_id" v-on:change="getResults" class="form-control">
                                    <option></option>
                                    <option v-bind:value="status.id"
                                            v-for="status of getStatus" :key="status.id">
                                        {{status.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="mr-sm-2" >Create by</label>
                                <input list="create-by" class="form-control" type="text" v-model="search.create_by" v-on:keyup="getResults">
                                <datalist id="create-by">
                                    <option v-bind:value="create_by.id"
                                            v-for="create_by of getCreateBy" :key="create_by.id">
                                    </option>
                                </datalist>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th v-on:click="getSort('Id')">ID
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetCode')">Asset Code
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetName')">Asset Name
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetLocation')">Location
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetCategory')">Category
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetInstalledDate')">Installed Date
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('Status')">Status
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(asset, index) in assets.data" :key="asset.id">
                                <td>{{ index+1 }}</td>
                                <td>{{asset.asset_code}}</td>
                                <td>{{asset.name}}</td>
                                <td >{{asset.asset_location.name}}</td>
                                <td>{{asset.asset_category.name}}</td>
                                <td >{{asset.installed_date}}
                                <td v-if="asset.assignment"><span class="badge badge-warning">Assigned</span></td>
                                <td v-else><span class="badge badge-success">Available</span></td>
                                <td>
                                    <a href="" v-on:click.prevent="detailAsset(asset)">Detail</a>
                                    <router-link v-if="checkRoles === 1" :to="{name: 'edit_asset', params: { id: asset.id }}">Edit</router-link>
                                    <a v-if="checkRoles === 1" href="#" @click="deleteAsset(asset.id)">Delete</a>
                                    <a v-if="asset.assignment && asset.assignment.status_id === 2" href="#" v-on:click.prevent="postReturn(asset.assignment)">Return</a>
                                    <a v-else href="#" readonly style="color:currentColor;cursor: not-allowed;opacity: 0.5;text-decoration: none">Return</a>
                                    <div v-if="$store.getters.getUser.is_admin === 1">
                                        <a v-if="asset.assignment" readonly style="color:currentColor;cursor: not-allowed;opacity: 0.5;text-decoration: none">CreateAssign</a>
                                        <a v-else href="#" v-on:click.prevent="createAssignment(asset)">CreateAssign</a>
                                    </div>
                                    <router-link :to="{name: 'ListAssetHistory', params: {id: asset.id}}">History</router-link>
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
                                <base-pagination v-bind:pagination="assets" v-bind:pages="pages" v-on:click.native="getResults"></base-pagination>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <p>{{ detail.name }}</p>
                        </div>
                        <div class="form-group" v-if="detailAssignment">
                            <label class="col-form-label">Assigned</label>

                            <p> <strong><i>Name:</i></strong> {{ detailAssignment.name }}</p>
                            <p> <strong><i>Staff Code:</i></strong> {{ detailAssignment.staff_code }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Asset Code:</label>
                            <p>{{ detail.asset_code }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Asset Location:</label>
                            <p>{{detailLocation}}</p>
                        </div>
                        <div class="form-group">
                            <label  class="col-form-label">Specs:</label>
                            <div v-if="detail.asset_detail">
                                <div v-for="spec in this.detail.asset_detail">
                                    <label>{{spec.name}}</label>
                                    <p>{{spec.value}}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example">User details</h5>
                        <select v-if="list_user.length !== 0" v-model="form.user_id" class ="form-control" v-on:change="changeUser($event)" >
                            <option v-for="user in list_user.data" :value="user.id" :key="user.id">
                                {{user.name}} ( {{user.staff_code}} )
                            </option>
                        </select>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if = "user != null">
                        <div class="form-group">
                            <label class="col-form-label">UserName</label>
                            <p>{{ this.user.name }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Staff Code:</label>
                            <p>{{ this.user.staff_code }}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Location:</label>
                            <p>{{this.locationName}}</p>
                        </div>
                        <div class="form-group">
                            <label  class="col-form-label">Date of Birth:</label>
                            <p>{{this.user.date_of_birth}}</p>
                        </div>
                        <div class="form-group">
                            <label  class="col-form-label">Join date:</label>
                            <p>{{this.user.joined_date}}</p>
                        </div>
                        <div class="form-group">
                            <label  class="col-form-label">Email:</label>
                            <p>{{this.user.email}}</p>
                        </div>
                        <div class="form-group">
                            <label  class="col-form-label">Gender:</label>
                            <p v-if="this.user.gender === 1">Male</p>
                            <p v-else>Female</p>
                        </div>
                        <div class="form-group">
                            <label  class="col-form-label">Type:</label>
                            <p v-if="this.user.is_admin === 0">Staff</p>
                            <p v-else>Admin</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-on:click="assignment" class="btn btn-success">Create Assignment</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import BaseButton from "../BaseComponent/BaseButton";
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";
import Vue from "vue";

export default {
    name: 'ListAssetTable',
    components: {BasePagination, BaseButton},
    computed: {
        checkRoles(){
            return  this.$store.getters.getUser.is_admin;
        }
    },
    data() {
        return {
            page:{
                current_page : 1,
            },
            column: 'Status',
            assets: {},
            detail: {},
            detailLocation: '',
            detailAssignment: '',
            categories: [],
            locations: [],
            assetNameCodeFields: null,
            sort: 'asc',
            checkedNames: [],
            locationFields: [],
            dateFields: [],
            ramFields: [],
            cpuFields: [],
            statusFields: [],
            limit: 10,
            statuses : [
                {
                    id: 1,
                    name: 'Assigned',
                },
                {
                    id : 2,
                    name: 'Available'
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
            ],
            statusAssignment: {},
            user: {},
            list_user: [],
            locationName: null,
            form: {},
            getAssetCode:[],
            getName:[],
            getLocation:[],
            getCategory:[],
            getInstalledDate:[],
            getCreateBy:[],
            getStatus:[],
            getSpecification: {
                '1':{
                    '1':{
                        'specification_id' : 1,
                        'name' : 'RAM',
                        'value' : [],
                    },
                    '2':{
                        'specification_id' : 2,
                        'name' : 'CPU',
                        'value' : [],
                    },
                    '3':{
                        'specification_id' : 3,
                        'name' : 'HHD',
                        'value' : [],
                    },
                },
                '2':{
                    '1':{
                        'specification_id' : 1,
                        'name' : 'RAM',
                        'value' : [],
                    },
                    '2':{
                        'specification_id' : 2,
                        'name' : 'CPU',
                        'value' : [],
                    },
                    '4':{
                        'specification_id' : 4,
                        'name' : 'Display',
                        'value' : [],
                    },
                },
                '3': {
                    '5':{
                        'specification_id' : 5,
                        'name' : 'Screen size',
                        'value' : [],
                    },
                    '6':{
                        'specification_id' : 6,
                        'name' : 'Bright',
                        'value' : [],
                    },
                },
            },
            search:{
            },
            specifications:{},
            assetCategory: null,
            specification:{},
            checkFilter: 0,
        };
    },
    created() {
        this.getResults();
    },
    methods: {
        changeCategory(e)
        {
            this.specification = {};
            this.specifications = {};
            this.specification = this.getSpecification[e.target.value];
            this.getResults();
        },
        getResults() {
            let url =  this.getQuery();
            axios.get(url)
                .then((res) => {
                this.assets = res.data;
                this.categories = Vue.prototype.$collections.asset_categories;
                this.locations = Vue.prototype.$collections.locations;
                if(this.checkFilter === 0)
                {
                    this.getAllResult(this.assets.total);
                }
            }).catch(e=> console.log(e));
        },
        getAllResult(total){
            this.checkFilter = 1;
            let url = APIConstants.asset + APIConstants.baseQueryURL(this.column,this.sort,total,1);
            axios.get(url).then((res) => {
                let assets = res.data;
                let asset;
                this.getAssetCode = [];
                this.getCreateBy = [];
                this.getName = [];
                this.getCategory = [];
                this.getLocation = [];
                this.getStatus = [];
                this.getSpecification= {
                    '1' : {
                        '1':{
                            'specification_id' : 1,
                            'name' : 'RAM',
                                'value' : [],
                        },
                        '2':{
                            'specification_id' : 2,
                            'name' : 'CPU',
                                'value' : [],
                        },
                        '3':{
                            'specification_id' : 3,
                            'name' : 'HHD',
                                'value' : [],
                        },
                    },
                    '2' : {
                        '1':{
                            'specification_id' : 1,
                            'name' : 'RAM',
                                'value' : [],
                        },
                        '2':{
                            'specification_id' : 2,
                            'name' : 'CPU',
                                'value' : [],
                        },
                        '4':{
                            'specification_id' : 4,
                            'name' : 'Display',
                                'value' : [],
                        },
                    },
                    '3' : {
                        '5':{
                            'specification_id' : 5,
                            'name' : 'Screen size',
                                'value' : [],
                        },
                        '6':{
                            'specification_id' : 6,
                            'name' : 'Bright',
                                'value' : [],
                        },
                    },
                };
                for(asset of assets.data)
                {
                    this.getAssetCode.push(asset.asset_code);
                    if(asset.name)
                    {
                       if(this.getName.length === 0)
                       {
                           this.getName.push(asset.name);
                       }
                       else
                       {
                           if(this.getName.findIndex(i =>i === asset.name) === -1)
                           {
                               this.getName.push(asset.name);
                           }
                       }
                    }
                    if(asset.asset_create_by)
                    {
                        if(this.getCreateBy.length === 0)
                        {
                            this.getCreateBy.push(asset.asset_create_by);
                        }
                        else
                        {
                            if(this.getCreateBy.findIndex(i =>i.id === asset.asset_create_by) === -1)
                            {
                                this.getCreateBy.push(asset.asset_create_by);
                            }
                        }
                    }
                    if(asset.asset_category_id)
                    {
                        let asset_detail;
                        for(asset_detail of asset.asset_detail)
                        {
                            if(this.getSpecification[asset.asset_category_id][asset_detail.specification_id].value.length === 0)
                            {
                                this.getSpecification[asset.asset_category_id][asset_detail.specification_id].value.push(asset_detail);
                            }
                            else
                            {
                                if(this.getSpecification[asset.asset_category_id][asset_detail.specification_id].value.findIndex
                                (i => (i.value === asset_detail.value && i.specification_id === asset_detail.specification_id)) === -1)
                                {
                                    this.getSpecification[asset.asset_category_id][asset_detail.specification_id].value.push(asset_detail);
                                }
                            }
                        }
                    }
                }
                this.getCategory = this.categories;
                this.getLocation = this.locations;
                this.getStatus = [
                    {
                        'id' : 1,
                        'name' : 'Available'
                    },
                    {
                        'id' : 2,
                        'name' : 'Assigned'
                    }
                ]
            });
        },
        getQuery(){
            let url = APIConstants.asset + APIConstants.baseQueryURL(this.column,this.sort,this.limit,this.page.current_page);
            $.each(this.search, function(key,value){
                if(value !== "")
                {
                    url += `&${key}=${value}`;
                }
            })
            if(Object.entries(this.specifications).length !== 0)
            {
                for(let [key,value] of Object.entries(this.specifications))
                {
                    if(value === '')
                    {
                        delete this.specifications[key];
                    }
                    else
                    {
                        url += `&specification${key}=${value}`;
                    }
                }
            }
            if(Object.entries(this.specifications).length !== 0)
            {
                url += `&specifications=""`;
            }
            return url;
        },
        getSearch(){
            let url = this.getQuery();
            axios.get(url, {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res, ) => {
                this.assets = res.data;
            });
        },
        deleteAsset(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete("/api/assets/" + id, {
                            headers: {
                                "Authorization" : APIConstants.cookie
                            },
                        })
                        .then((response) => {
                            swal.fire({
                                icon: 'success',
                                title: 'Delete asset successfully!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.getResults();
                        })
                        .catch((error) => {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Cannot delete this asset because it currently assigned!',
                            })
                        });
                }
            });
        },

        updateOrderDirection() {
            if (this.query.order_direction === "desc") {
                this.query.order_direction = "asc";
            } else {
                this.query.order_direction = "desc";
            }
        },

        getSort(column){
            if (this.sort === 'asc'){
                this.sort = 'desc';
            }else{
                this.sort = 'asc';
            }
            this.column = column;
            this.getResults();
        },

        uniqueCheck(e){
            this.additional_grouped = [];
            if (e.target.checked) {
                this.additional_grouped.push(e.target.value);
            }
        },
        detailAsset(asset){
            this.detail = asset;
            this.detailLocation = asset.asset_location.name;
            if(asset.assignment){
                this.detailAssignment = asset.assignment.user_assignment;
            }
            else {
                this.detailAssignment = null;
            }
            $('#exampleModal').modal('show');
        },
        getLimit(event){
            this.limit = event.target.value;
            this.assets.current_page =1;
            this.getResults();
        },
        postReturn(data){
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, return it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.createRequest(data);
                }
            })
        },
        async createRequest(data){
            const check = await APIConstants.edit(APIConstants.assign+"/"+data.id,{status_id: 1, is_assigned : 1});
            if(check !== 0)
            {
                await APIConstants.post(APIConstants.request,{user_id: data.user_id, assignment_id: data.id, is_returned:  1,created_by: this.$store.getters.getUser.id}).then(res => {
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
                        swal.fire(
                            'Return!',
                            'Your asset has been request return.',
                            'success'
                        )
                    }
                }).catch(error => {
                });
                this.getResults();
            }
            else
            {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This assignment have a request return already!',
                    footer: '<a href>Why do I have this issue?</a>'
                })
                this.getResults();
            }
            this.$forceUpdate();
        },
        async createAssignment(asset){

            this.list_user = await APIConstants.get(`${APIConstants.user}`);
            this.form.asset_id = asset.id;
            $('#modalCreate').modal('show');
        },
        changeUser(e){
            let value = e.target.value;
            let display = this.list_user.data.find(d => d.id == value);
            this.user = display;
            this.locationName = this.user.user_location.name;
        },
        async assignment(){
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
                    const data = await APIConstants.create(`${APIConstants.assign}`,this.form);
                    if(data === 'This asset is belongs to another user , cannot create assignment')
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
                        swal.fire(
                            'Create!',
                            'Create assignment success.',
                            'success'
                        )
                    }
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
            this.getResults();
        }
    },
};
</script>

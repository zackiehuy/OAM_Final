<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-sm-offset-2 row">
                            <h3 class="row ml-1 ">Create Asset</h3>
                            <router-link to="/assets" class = "ml-auto mr-3">
                                <button class="btn btn-success "> <i class="fa fa-reply" aria-hidden="true"></i> Back to list</button>
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assets Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class=" col-sm-4" id="settings">
                                    <div class="form-group">
                                        <label for="a">Asset Code</label>
                                        <input type="text" class="form-control" use :value = "this.asset_history.asset_code" id="a" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="b">Asset Name</label>
                                        <input type="text" class="form-control" use :value = "this.asset_history.name" id="b" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="c">Location</label>
                                        <input v-if="this.asset_history.asset_location" type="text" class="form-control" use :value = "this.asset_history.asset_location.name" id="c" readonly>
                                    </div>
                                </div>
                                <div class=" col-sm-4" >
                                    <div class="form-group">
                                        <label for="d">Asset Category</label>
                                        <input v-if="this.asset_history.asset_category" type="text" class="form-control" use :value = "this.asset_history.asset_category.name" id="d" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="e">Create by</label>
                                        <input v-if="asset_history.asset_user != null" type="text" class="form-control" use :value = "asset_history.asset_user.name" id="e" readonly>
                                        <input v-else type="text" class="form-control"  readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="f">Create at</label>
                                        <input type="text" class="form-control" use :value = "asset_history.created_at" id="f" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assets History</h3>
                    </div>
                    <!-- /.card-header -->
                    <div v-if="assignment.length === 0">
                        <h3 class ="p-4" align="center">This asset don't have any assignment</h3>
                    </div>
                    <div v-else>
                    <div class="card-body table-responsive p-0" >
                        <div class="container mx-1" >
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Staff</label>
                                    <input list="user" type="text" class="form-control" v-model="search.UserId" v-on:keyup="ListAssetHistory">
                                    <datalist id="user">
                                        <option v-bind:value="user.id"
                                                v-for="user of getUser" :key="user.id">
                                            StaffCode: {{user.staff_code}}, Username: {{user.name}}
                                        </option>
                                    </datalist>
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Started Date</label>
                                    <input class="form-control" type="date" v-model="search.StartedDate" v-on:change="ListAssetHistory">
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >End Date</label>
                                    <input type="date" class="form-control" v-model="search.EndDate" v-on:change="ListAssetHistory">
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Status</label>
                                    <select class ="form-control" v-model="search.Status"
                                            name = "assignment" v-on:change="ListAssetHistory">
                                        <option></option>
                                        <option v-bind:value="status.status_id"
                                                v-for="status in getStatus" :key="status.status_id">
                                            {{status.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="mr-sm-2" >Create By</label>
                                    <input list="createBy" class="form-control" v-model="search.CreateBy" v-on:keyup="ListAssetHistory">
                                    <datalist id="createBy">
                                        <option v-bind:value="createBy.id"
                                                v-for="createBy of getCreateBy" :key="createBy.id">
                                            StaffCode: {{createBy.staff_code}}, Username: {{createBy.name}}
                                        </option>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                            <table class="table table-hover">
                                <div class="card-body">
                                </div>
                                <tbody>
                                <tr>
                                    <th>#</th>
                                    <th v-on:click="getSort('AssignTo')">
                                        Assign to
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('AssignBy')">
                                        Assign by
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('StartedDate')">
                                        Started date
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('EndDate')">
                                        End date
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('Status')">
                                        Status
                                        <i class="fas fa-sort"></i>
                                    </th>
                                </tr>
                                <tr v-for="(list,index) of assignment.data" >
                                    <td>{{index+1}}</td>
                                    <td>{{list.user.name}}</td>
                                    <td v-if="list.create_by != null">{{list.create_by.name}}</td>
                                    <td v-else></td>
                                    <td>{{list.started_date | formatDate}} </td>
                                    <td v-if="list.end_date != null">{{list.end_date | formatDate}}</td>
                                    <td v-else></td>
                                    <td v-if="list.status_id == 2" style="color:green;" ><strong>{{list.status.name}}</strong></td>
                                    <td v-else-if="list.status_id == 4" style="color:brown;" ><strong>{{list.status.name}}</strong></td>
                                    <td v-else-if="list.status_id == 3" style="color:red;" ><strong>{{list.status.name}}</strong></td>
                                    <td v-else-if="list.status_id == 1 && list.is_assigned === 0" style="color:dodgerblue;" ><strong>{{list.status.name}}</strong></td>
                                    <td v-else-if="list.status_id == 1 && list.is_assigned === 1" style="color:dodgerblue;" ><strong>Waiting to return</strong></td>
                                </tr>
                                </tbody>
                            </table>
                    </div>
                    <div class="card-footer">
                        <div>
                            <select v-model="limit" v-on:change="getLimit()" class = "form-control col-sm-2">
                                <option value ="1">1</option>
                                <option value="2">2</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <base-pagination v-bind:pagination="assignment" v-on:click.native="ListAssetHistory"></base-pagination>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";
export default {
    components: {BasePagination},
    data(){
        return{
            asset_history: {},
            pagination: {
                current_page: 1
            },
            column: 'StartedDate',
            sort: 'asc',
            assignment: [],
            assignmentIsNull: 1,
            limit: 10,
            assign_by: [],
            status: [],
            startedDate: {
                started_date: [],
                started_day: [],
                started_month: [],
                started_year: [],
            },
            endDate: {
                end_date: [],
                end_day: [],
                end_month: [],
                end_year: [],
            },
            assign_to: "",
            url : null,
            filterAssignTo: "",
            assignBy: "",
            filterAssignBy: "",
            limitchange: 10,
            StartedDate: "",
            starteddate: "",
            EndDate: "",
            search: {},
            getUser: [],
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
            getCreateBy: [],
            checkFilter: 0,
        }
    },
    methods: {
        getUrl(){
            let url = APIConstants.asset + `/${this.$route.params.id}/history` +
                APIConstants.baseQueryURL(this.column,this.sort,this.limit,this.pagination.current_page);
            $.each(this.search,function(key,value){
                if(value !== "")
                {
                    url += `&${key}=${value}`;
                }
            });
            return url;
        },
        async ListAssetHistory() {
                let url = this.getUrl();
            const data = await APIConstants.get(url);
            if(data === "This asset is not available")
            {
                alert("This asset is not available");
                this.$router.push('/assets/');
            }
            else if(data === "This asset is not belong to you")
            {
                alert("This asset is not belong to you");
                this.$router.push('/assets/');
            }
            this.asset_history = data.asset;
            this.assignment = data.assignment;
            this.pagination = data.assignment;
            if(this.checkFilter === 0)
            {
                this.ListAssignment(this.assignment.total);
            }
        },
        async ListAssignment(total) {
            this.checkFilter = 1;
            let url = APIConstants.asset + `/${this.$route.params.id}/history` +
                APIConstants.baseQueryURL(this.column,this.sort,total,1);
            const data = await APIConstants.get(url);
            if(data === "This asset is not available")
            {
                alert("This asset is not available");
                this.$router.push('/assets/');
            }
            else if(data === "This asset is not belong to you")
            {
                alert("This asset is not belong to you");
                this.$router.push('/assets/');
            }
            let assignment;
            console.log(data.assignment.data);
            for(assignment of data.assignment.data)
            {
                if(assignment.user)
                {
                    if(this.getUser.length === 0)
                    {
                        this.getUser.push(assignment.user);
                    }
                    else
                    {
                        if(this.getUser.findIndex(i => i.id === assignment.user.id) === -1)
                        {
                            this.getUser.push(assignment.user)
                        }
                    }
                }
                if(assignment.create_by)
                {
                    if(this.getCreateBy.length === 0)
                    {
                        this.getCreateBy.push(assignment.create_by);
                    }
                    else
                    {
                        if(this.getCreateBy.findIndex(i => i.id === assignment.create_by.id) === -1)
                        {
                            this.getCreateBy.push(assignment.create_by);
                        }
                    }
                }
            }
            console.log(assignment);
        },
        getSort(column) {
            if (this.sort == 'asc'){
                this.sort = 'desc';
            }else{
                this.sort = 'asc';
            }
            this.column = column;
            this.ListAssetHistory();
        },
        getLimit(){
            this.ListAssetHistory();
        },
    },
    mounted() {
        this.ListAssetHistory(this.pagination.current_page, this.column, this.sort, this.limit);
    }
}
</script>





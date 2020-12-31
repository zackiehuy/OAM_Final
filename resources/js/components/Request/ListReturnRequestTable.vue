<template>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Return-request list</h3>
                </div>
                <div class="card-header">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="card-title"> Filter</h3>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label>Status</label>
                                <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                                       v-model="search.status" v-on:keyup="searchRequests">
                            </div>
                            <div class="form-group">
                                <label>Staff Name</label>
                                <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                                       v-model="search.user_id" v-on:keyup="searchRequests">
                            </div>
                            <div class="form-group">
                                <label>Assignment Id</label>
                                <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                                       v-model="search.assignment_id" v-on:keyup="searchRequests">
                            </div>
                            <div class="form-group">
                                <label>Requested Date</label>
                                <input class="form-control" type="date" placeholder="Search" aria-label="Search"
                                       v-model="search.requested_date" v-on:change="searchRequests">
                            </div>
                            <div class="form-group">
                                <label>Response Date</label>
                                <input class="form-control" type="date" placeholder="Search" aria-label="Search"
                                       v-model="search.response_date" v-on:change="searchRequests">
                            </div>
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
                                Assignment Id
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
                            <th v-on:click="getSort('Status')">
                                Status
                                <i class="fas fa-sort"></i>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(request,index) of list_requests.data" :key="request.id">
                                <td>{{ index +1 }}</td>
                                <td>{{ request.user.name }}</td>
                                <td>{{ request.assignment_id }}</td>
                                <td>{{ request.requested_date | moment("DD/MM/YYYY")}}</td>
                                <td>{{ request.response_date}}</td>
                                <td v-if="request.status === 1">
                                    <div v-if="checkRoles === 1">
                                        <a style="color:green;" href="#" v-on:click="changeStatus(request.id, 2)"><strong>Accept</strong></a> |
                                        <a style="color:red;" href="#" v-on:click="changeStatus(request.id, 3)"><strong>Decline</strong></a>
                                    </div>
                                    <div v-if="checkRoles === 0">
                                        <strong style="color:blue;">Waiting</strong>
                                    </div>
                                </td>
                                <td v-if="request.status === 2">
                                    <strong style="color:green;">Accepted</strong>
                                </td>
                                <td v-if="request.status === 3">
                                    <strong style="color:red;">Declined</strong>
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
            <!-- /.card -->
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios';

import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";
export default {
    name: "ListRequestTable",
    components:{
        BasePagination
    },
    data(){
        return{
            list_requests: [],
            column: 'Status',
            sort: 'asc',
            limit: 10,
            search: {},
        }
    },
    computed: {
        checkRoles(){
            return  this.$store.getters.getUser.is_admin;
        }
    },
    created() {
        this.getListRequests();
        console.log(this.checkRoles)
    },
    methods: {
        searchRequests() {
            let query = this.getUrl();
            axios.get(query).then((res) => {
                this.list_requests = res.data;
            })
        },
        getUrl(){
            let query = APIConstants.request + '?admin_request&column=' + this.column
                + '&sort=' + this.sort
                + '&limit=' + this.limit;
            $.each(this.search, function (key, value) {
                if (value !== '') {
                    query += '&' + key + '=' + value;
                } else {
                }
            })
            return query;
        },
        getListRequests() {
            let url = this.getUrl();
            axios.get(url+ '&page=' + this.list_requests.current_page)
                .then(response => {
                    this.list_requests = response.data
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },
        changeStatus(id, statusId) {
            swal.fire({
                title: 'Do you want to save the changes?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Save`,
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.patch((APIConstants.request + "/" + id), {
                        status: statusId
                    }, {
                        headers: {
                            "Authorization": APIConstants.cookie
                        }
                    })
                        .then(response => {
                            this.getListRequests();
                        })
                } else if (result.isDenied) {
                    this.getListRequests();
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

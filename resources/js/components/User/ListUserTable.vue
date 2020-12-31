<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users Table</h3>
            <div class="card-tools">
              <base-button @click.native="navigateToCreate">
                Add New <i class="fas fa-user-plus fa-fw"></i>
              </base-button>
                <button style="margin: 30px" @click="reset" class="btn btn-success"> Reset</button>
            </div>
          </div>
          <!-- Search form -->
          <!-- /.card-header -->
          <div class="container">
            <div class="row">
              <div class="col-sm-3">
                <label>Staff Code</label>
                <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                       v-model="search.staff_code" v-on:keyup="searchUsers">
              </div>
              <div class=" col-sm-3">
                <label>Name</label>
                <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                v-model="search.name" v-on:keyup="searchUsers">
              </div>
              <div class=" col-sm-3">
                <label>Email</label>
                <input class="form-control" type="email" placeholder="Search" aria-label="Search"
                       v-model="search.email" v-on:keyup="searchUsers">
              </div>
              <div class=" col-sm-3">
                <label>Location</label>
                  <select class="form-control" v-model="search.location_id"
                          style="width: 100%;" name="location" v-on:change="searchUsers">
                      <option>
                      </option>
                      <option v-bind:value="location.id"
                            v-for="location in locations" :key="location.id">
                      {{ location.name }}
                    </option>
                  </select>
              </div>
              <div class=" col-sm-3">
                <label>Gender</label>
                  <select class="form-control select2" v-model="search.gender"
                          style="width: 100%;" name="gender" v-on:change="searchUsers">
                      <option></option>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                  </select>
              </div>
              <div class=" col-sm-3">
                <label>Type</label>
                    <select class="form-control select2" v-model="search.is_admin"
                            style="width: 100%;" name="type" v-on:change="searchUsers">
                        <option></option>
                      <option value="0">Admin</option>
                      <option value="1">Staff</option>
                    </select>
              </div>
              <div class=" col-sm-3">
                <label>Date of birth</label>
                <input type="date" v-model="search.date_of_birth"
                      class="form-control date-format"
                      id="inputDateOfBirth" v-on:change="searchUsers"/>
              </div>
              <div class=" col-sm-3">
                <label>Joined date</label>
                <input type="date" v-model="search.joined_date"
                      class="form-control date-format"
                      id="inputJoinedDate" v-on:change="searchUsers"/>
              </div>
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <thead>
              <tr>
                <th @click="getSort('StaffCode')">Staff Code</th>
                <th @click="getSort('UserName')">Name</th>
                <th @click="getSort('UserEmail')">Email</th>
                <th @click="getSort('UserLocation')">Location</th>
                <th @click="getSort('UserType')">Type</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody v-for="user in users.data" :key="user.id">
              <tr>
                <th scope="row">{{ user.staff_code }}</th>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.user_location.name }}</td>
                <td v-if="user.is_admin == 1">Admin</td>
                <td v-else>Staff</td>
                <td>
                  <router-link v-bind:to="{ name: 'ShowUsers', params: { id: user.id }}">Detail</router-link>
                  <a href="#" v-on:click="checkAsset(user.id)">CreateAssignment</a>
                  <a href="#" @click="displayNotify(user.id)">Delete</a>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-6">
                <p><strong>Per Page:</strong>
                    <select v-on:change="getLimit($event)">
                        <option value="10" selected>10</option>
                        <option value="5">5</option>
                        <option value="3">3</option>
                        <option value="1">1</option>
                    </select>
                  </p>
              </div>
              <div class="col-sm-6">
                <base-pagination v-bind:pagination="users" v-on:click.native="getUsers()"></base-pagination>
              </div>
            </div>
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
                              <div v-for="spec in this.assignment.asset.asset_detail">
                                  <label>{{spec.name}}</label>
                                  <p>{{spec.value}}</p>
                              </div>
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
import axios from 'axios'
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";
import BaseButton from "../BaseComponent/BaseButton";
import Vue from "vue";

export default {
  components: {BasePagination, BaseButton},
  data() {
    return {
      users: {},
      locations: {},
      column: 'StaffCode',
      sort: 'asc',
      search: {},
      limit: '',
        form: {},
        list_assets: [],
        assignment:{},
        assetLocation: {},
    }
  },
  async created() {
    this.getUsers();
  },
  methods: {
    getUrl(){
      let query = APIConstants.user + '?&column=' + this.column + '&sort=' + this.sort + '&limit=' + this.limit;
      $.each(this.search, function (key, value) {
        if (value !== '') {
          query += '&' + key + '=' + value;
        }
      })
      return query;
    },
      async getUsers() {
        const user = this.$store.getters.getUser;
        if(user.is_admin === 0)
        {
            this.$router.push(`/users/${user.id}`);
        }
        let query = this.getUrl();
        query += '&page=' + this.users.current_page;
        this.users = await APIConstants.get(query);
        this.locations = Vue.prototype.$collections.locations;
    },
    async searchUsers() {
      let query = this.getUrl();
      this.users = await APIConstants.get(query);
    },
    async  deleteUser(id) {
        await APIConstants.baseDelete(`${APIConstants.user}/${id}`).then(res => {
            console.log(res.data);
            if(res.data === "This user doesn't exist")
            {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "This user doen't exist!",
                });
            }
            else if(res.data === "Delete user success")
            {
                swal.fire(
                    'Deleted successfully'
                );
            }
            else
            {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "This user still have at least 1 assignment!",
                });
            }
        }).catch(e => {
            console.log(e);
        });
      this.getUsers(this.page, this.column, this.sort);
    },
    displayNotify(id) {
      swal.fire({
        title: 'Are you sure to delete this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.value === true) {
            this.deleteUser(id);
        }
      })
    },
    getSort(column) {
      if (this.sort == 'asc') {
        this.sort = 'desc';
      } else {
        this.sort = 'asc';
      }
      this.column = column;
      return this.getUsers();
    },
    navigateToCreate() {
      this.$router.push('users/create');
    },
    getLimit(event){
      this.limit = event.target.value;
      this.getUsers();
    },
    reset(){
        $("input, select").val("");
        this.search = {};
        this.getUsers();
    },
    async checkAsset(user_id){
        this.form.user_id = user_id;
        this.form.status_id = 2;
        this.list_assets = (await APIConstants.get(`${APIConstants.assign}/create`)).assets;
        if(this.list_assets.length === 0)
        {
            alert("This list Asset don't have any asset now");
        }
        else
        {
            $('.modal').modal('show');
        }
    },
      changeAssetCode(e)
      {
          this.assignment = {};
          this.assignment.asset = {};
          let value = e.target.value;
          let display = this.list_assets.find(d => d.id == value);
          this.assignment.asset = display;
          this.assetlocation = this.assignment.asset.asset_location.name;
          console.log(this.assignment);
      },
    async createAssignment(){
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
                    alert("This asset is belongs to another user , cannot create assignment");
                }
                this.getUsers();
            } else if (result.isDenied) {
                this.getUsers();
            }
        })
      }
  }
}
</script>

<style scoped>
.form-group {
  width: 15%;
  float: left;
  margin: 5px;
}
</style>

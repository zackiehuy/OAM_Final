<template>
  <div class="container">
    <div class="row">
      <!-- tab -->
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="tab-content form-group">
              <!-- Activity Tab -->
                    <div class="col-sm-offset-2 row">
                        <h3 class="row ml-1 ">Create User</h3>
                        <router-link to="/users" class = "ml-auto mr-3">
                            <button class="btn btn-success ">Back to list</button>
                        </router-link>
                    </div>
              <!-- Setting Tab -->
              <div class="tab-pane active show" id="settings">
                <form class="form-horizontal" v-on:submit.prevent="createUser">
                  <div class="form-group" style="background-color: #FFB6C1" v-if="this.errors !== null">
                    <div v-for="error in errors" :key="error.id">
                      <div>{{ error.toString() }}</div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-12">
                      <input type="text" v-model="form.name"
                             class="form-control"
                             id="inputName" placeholder="Name"
                             v-bind:class="listError.includes('name') ? 'is-invalid' : ''"/>
                    </div>

                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-12">
                      <input type="email" v-model="form.email"
                             class="form-control"
                             id="inputEmail" placeholder="Email"
                             v-bind:class="listError.includes('email') ? 'is-invalid' : ''"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label">Password</label>

                    <div class="col-sm-12">
                      <input type="password" v-model="form.password"
                             class="form-control"
                             id="inputPassword" placeholder="Password"/>

                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputDateOfBirth" class="col-sm-2 control-label">Date of birth</label>

                    <div class="col-sm-12">
                      <input type="date" v-model="form.date_of_birth"
                             class="form-control date-format" id="inputDateOfBirth"
                             v-bind:class="listError.includes('date_of_birth') ? 'is-invalid' : ''"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputJoinedDate" class="col-sm-2 control-label">Joined Date</label>

                    <div class="col-sm-12">
                      <input type="date" v-model="form.joined_date"
                             class="form-control date-format" id="inputJoinedDate"
                             v-bind:class="listError.includes('joined_date') ? 'is-invalid' : ''"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Gender</label>

                    <div class="col-sm-12">
                      <div class="select2-purple">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" v-model="form.gender"
                                 type="radio" name="gender"
                                 id="gender1" value="0"
                                 v-bind:class="listError.includes('gender') ? 'is-invalid' : ''">
                          <label class="form-check-label" for="gender1">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" v-model="form.gender"
                                 type="radio" name="gender" id="gender2" value="1"
                                 v-bind:class="listError.includes('gender') ? 'is-invalid' : ''">
                          <label class="form-check-label" for="gender2">Female</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Type</label>

                    <div class="col-sm-12">
                      <div class="select2-purple">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" v-model="form.is_admin"
                                 type="radio" name="type" id="type1" value="1">
                          <label class="form-check-label" for="type1">Admin</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" v-model="form.is_admin"
                                 type="radio" name="type" id="type2" value="0">
                          <label class="form-check-label" for="type2">Staff</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Location</label>

                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <select class="form-control select2" v-model="form.location_id"
                                style="width: 100%;" name="location"
                                v-bind:class="listError.includes('location_id') ? 'is-invalid' : ''">
                          <option selected value="">Select location</option>
                          <option v-bind:value="location.id"
                                  v-for="location in locations" :key="location.id">
                            {{ location.name }}
                          </option>
                        </select>
                      </div>
                    </div>
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
  </div>
</template>

<script>


import axios from "axios";
import {APIConstants} from "../../constants";
import Vue from "vue";
import moment from "moment";

export default {
  data() {
    return {
      locations: [],
      form: {
        staff_code: '',
        name: '',
        email: '',
        password: '',
        date_of_birth: '',
        joined_date: '',
        gender: 1,
        is_admin: 1,
        location_id: ''
      },
      errors: {},
      listError: []
    }
  },
  methods: {
    getLocation() {
        this.locations = Vue.prototype.$collections.locations;
    },
      async createUser() {
          swal.fire({
              title: 'Do you want to create this user?',
              showDenyButton: true,
              showCancelButton: true,
              confirmButtonText: `Yes`,
              denyButtonText: `No`,
          }).then(async(result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                  const checkCreate = await APIConstants.create(APIConstants.user, this.form).catch((error) => {
                      this.errors = error.response.data.errors;
                      this.listError = Object.keys(error.response.data.errors);
                      swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Create failed!',
                          footer: '<a href="/users">Back to list</a>'
                      })
                  });
                  if(checkCreate)
                  {
                      if(checkCreate.id == undefined)
                      {
                          swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Create failed!',
                              footer: '<a href="/users">Back to list</a>'
                          })
                      }
                      else
                      {
                          swal.fire({
                              title: 'Create successfully!'
                          });
                          this.$router.push('/users');
                      }
                  }
              } else if (result.isDenied) {
                  swal.fire('Changes are not saved', '', 'info')
              }
          })
      }
  },
  created() {
    this.getLocation();
  }
};
</script>
<style>

</style>

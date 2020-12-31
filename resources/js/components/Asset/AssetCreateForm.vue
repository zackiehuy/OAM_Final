<template>
    <div class="container">
        <div class="row">
            <!-- tab -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="col-sm-offset-2 row">
                                <h3 class="row ml-1 ">Create Asset</h3>
                                <router-link to="/assets/" class = "ml-auto mr-3">
                                    <button class="btn btn-success "> <i class="fa fa-reply" aria-hidden="true"></i> Back to list</button>
                                </router-link>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <div class="alert alert-danger" v-if="errors !== null    ">
                                    <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="(v, k) in errors" :key="k">
                                            {{ v.toString() }}
                                        </li>
                                    </ul>

                                </div>
                                <form class="form-horizontal" @submit.prevent="createAsset()" >
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label"
                                        >Asset Code</label>
                                        <div class="col-sm-12">
                                            <input
                                                class="form-control"
                                                placeholder="Asset code"
                                                v-model="asset.asset_code"
                                                name="asset_code"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label"
                                        >Asset Name</label
                                        >
                                        <div class="col-sm-12">
                                            <input
                                                class="form-control"
                                                id="inputName"
                                                placeholder="Name"
                                                v-model="asset.name"
                                                name="name"
                                                v-bind:class="error.includes('name') ? 'is-invalid' : ''"

                                            />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                        >Location</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2" style="width: 100%;"
                                                    v-model="asset.location_id" id="location"
                                                    v-bind:class="error.includes('location_id') ? 'is-invalid' : ''"
                                            >
                                                <option v-for="location in locations"
                                                        :key="location.id" :value="location.id"
                                                >{{ location.name }}</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label"
                                        >Description</label
                                        >
                                        <div class="col-sm-12">
                                          <textarea
                                              class="form-control"
                                              id="inputExperience"
                                              placeholder="Description"
                                              v-model="asset.description"
                                              name="desc"
                                          ></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="password" class="col-sm-12 control-label"
                                        >Installed Date</label
                                        >

                                        <div class="col-sm-12">
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="password"
                                                placeholder="Passport"
                                                v-model="asset.installed_date"
                                                name="install"
                                                v-bind:class="error.includes('installed_date') ? 'is-invalid' : ''"
                                            />
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                        >Category</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2" style="width: 100%;"
                                                    v-model="asset.asset_category_id" id="category"
                                                    v-bind:class="error.includes('asset_category_id') ? 'is-invalid' : ''"
                                                    v-on:change="changeCategory($event)"
                                            >
                                                <option v-for="category in categories"
                                                        :key="category.id" :value="category.id"
                                                >{{ category.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                        >Specification</label>
                                        <div class="col-sm-12" v-if="changeCategoryId != null">
                                            <div v-for="specification in specifications[changeCategoryId]">
                                                <label>{{specification.name}}</label>
                                                <input v-model="asset.specifications[specification.specification_id] = specification.default_value" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-center">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success">
                                                Submit
                                            </button>
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
import {APIConstants} from "../../constants";
import Vue from "vue";
import moment from "moment"
export default {
    data() {
        return {
            categories: [],
            locations: [],
            errors : null,
            asset : {},
            error: [],
            cate_specs: [],
            specifications: [],
            changeCategoryId : null,
        }
    },
    created() {
        this.fetchAsset();
    },
    methods:{
        async createAsset(){
            console.log(this.asset);
            // swal.fire({
            //     title: 'Do you want to create this asset?',
            //     showDenyButton: true,
            //     showCancelButton: true,
            //     confirmButtonText: `Yes`,
            //     denyButtonText: `No`,
            // }).then(async(result) => {
            //     /* Read more about isConfirmed, isDenied below */
            //     if (result.isConfirmed) {
            //         if(this.asset.installed_date == '')
            //         {
            //             this.asset.installed_date = moment(Date.now()).format('yyyy-MM-DD');
            //         }
            //         const data = await APIConstants.create('/api/assets', this.asset).catch(error => {
            //             swal.fire({
            //                 icon: 'error',
            //                 title: 'Oops...',
            //                 text: 'You can\'t create this asset now!',
            //                 footer: '<a href>Why do I have this issue?</a>'
            //             })
            //             this.errors = error.response.data.errors;
            //             this.error = Object.keys(this.errors);
            //         });
            //     } else if (result.isDenied) {
            //         swal.fire('Changes are not saved', '', 'info')
            //     }
            // })
        },
        fetchAsset() {
            axios.get("/api/assets/create", {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res) => {
                this.specifications = res.data;
                this.categories = Vue.prototype.$collections.asset_categories;
                this.locations = Vue.prototype.$collections.locations;
                this.cate_specs = Vue.prototype.$collections.category_specifications;
                this.asset.asset_code = '';
            });
        },
        changeCategory($event){
            this.changeCategoryId = $event.target.value - 1;
            this.asset.specifications = {};
        },
    }
}
</script>

<style scoped>

</style>

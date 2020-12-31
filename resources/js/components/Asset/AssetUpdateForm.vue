<template>
    <div class="container">
        <div class="row">
            <!-- tab -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">Update Asset</h3>
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
                                                placeholder="Asset Code"
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
                                        <div class="col-sm-12">
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
export default {
    data() {
        return {
            categories: [],
            locations: [],
            errors : null,
            asset : {
                asset_code : '',

            },
            cate_specs: [],
            selectedSpec: [],
            selected: '',
            error: [],
            specifications: [],
            changeCategoryId: null,
        }
    },
    created() {
        this.fetchAsset();
    },
    methods:{
        async createAsset(){
            swal.fire({
                title: 'Do you want to update this asset?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
            }).then(async(result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const data = await APIConstants.edit(`${APIConstants.asset}/${this.$route.params.id}`,this.asset).catch(e => {
                        this.errors = e.response.data.errors;
                        this.error = Object.keys(this.errors);
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Asset hasn't been updated",
                            footer: '<a href>Why do I have this issue?</a>'
                        });
                    });
                    if(data != undefined)
                    {
                        swal.fire({
                            icon: 'success',
                            title: 'Asset has been updated  ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.$router.push('/assets');
                    }
                    else
                    {
                        swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Asset hasn't been updated",
                            footer: '<a href>Why do I have this issue?</a>'
                        });
                    }
                } else if (result.isDenied) {
                    swal.fire('Changes are not saved', '', 'info')
                }
            })
        },
        async fetchAsset() {
            const data = await APIConstants.get("/api/assets/" + this.$route.params.id);
            this.asset = data.asset;
            this.asset.old_category = this.asset.asset_category_id;
            this.locations = Vue.prototype.$collections.locations;
            this.categories = Vue.prototype.$collections.asset_categories;
            this.specifications = data.require_specification;
            this.changeCategoryId = this.asset.asset_category_id - 1;
            this.asset.specifications = {};
        },
        changeCategory($e)
        {
            this.changeCategoryId = $e.target.value - 1;
            this.asset.specifications = {};
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <div class="container">
        <div class="row">
            <!-- tab -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-sm-offset-2 row">
                            <h3 class="row ml-1 ">Assigment request form</h3>
                            <router-link to="/request/" class = "ml-auto mr-3">
                                <button class="btn btn-success "> <i class="fa fa-reply" aria-hidden="true"></i> Back to list</button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">@@@@@@@</h3>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form v-on:submit.prevent="onSubmit">
                                    <div class="form-group" style="background-color: #FFB6C1" v-if="this.errors !== null">
                                        <div v-for="error in errors">
                                            <div>{{error.toString()}}</div>
                                        </div>
                                    </div>

                                    <div id="Cate">
                                        <label>Choose Category: </label>
                                        <select v-model="selected" id="cateName">
                                            <option v-for="cate of list_categories" :key="cate.id" v-bind:value="{ id: cate.id, name: cate.name }">{{ cate.name }}
                                            </option>
                                        </select>
                                        <i v-on:click="addCate" class="fas fa-plus-circle fa-fw"></i>
                                        <h6><strong>Selected Categories:</strong></h6>
                                        <div v-for="sc of selectedCate" class=".col">
                                            <i v-on:click="removeCate(sc)" class="fas fa-minus-circle fa-fw .col"></i>
                                            {{sc.name}}
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">Send</button>
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
import axios from 'axios'
import {APIConstants} from "../../constants";
import Vue from "vue";

export default {
    name: "CreatedForm",
    data() {
        return {
            range: 0,
            form: {},
            selected: '',
            selectedCate: [],
            list_categories: [],
            list_users: [],
            errors: {},
            IdUser: '',
        }
    },
    created() {
        this.getRequestForm();
    },
    methods: {
        async getRequestForm() {
            if(this.$store.getters.getUser.is_admin === 1)
            {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'You don\'t this permission!',
                    footer: '<a href>Why do I have this issue?</a>'
                })
                this.$router.push('/request');
            }
            this.list_categories = Vue.prototype.$collections.asset_categories;
        },
        addCate() {
            if (this.selected != '') {
                this.selectedCate.push(this.selected)
            }
        },
        removeCate(cate) {
            let index = this.selectedCate.indexOf(cate);
            this.selectedCate.splice(index, 1);
        },
        async onSubmit() {
            let selectedId = []
            //get category id
            for (let s = 0; s <= this.selectedCate.length - 1; s++) {
                selectedId.push(this.selectedCate[s].id);
            }
            if(selectedId.length === 0)
            {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'You need to add Asset Category to your list request!',
                    footer: '<a href>Why do I have this issue?</a>'
                })
            }
            else
            {
                this.form['user_id'] = this.$store.getters.getUser.id;
                this.form['created_by'] = this.form['user_id'];
                this.form['is_returned'] = 0;
                this.form['asset_category_id'] = selectedId;
                swal.fire({
                    title: 'Do you want to save the changes?',
                    showDenyButton: true,
                    confirmButtonText: `Save`,
                    denyButtonText: `Don't save`,
                }).then(async (result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        await APIConstants.post(APIConstants.request,this.form).then(res => {
                            swal.fire({
                                position: 'middle',
                                icon: 'success',
                                title: 'Your request has been create',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$router.push('/request/')
                        }).catch(e => {
                            console.log(e);
                        });
                    } else if (result.isDenied) {

                    }
                })
            }
        },
    }
}
</script>

<style scoped>

</style>

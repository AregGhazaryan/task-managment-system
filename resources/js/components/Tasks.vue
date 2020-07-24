<template>
    <div class="container mt-5">
        <input type="text" v-model="search" @keyup="searchResults()" class="form-control mb-4" placeholder="Search...">
        <div class="w-100 d-flex flex-row-reverse mb-3">
            <router-link to="/create" class="btn btn-success"
                >Create a Task</router-link
            >
        </div>
        <div class="table-responsive bg-white shadow-sm">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Task name</th>
                        <th>Created by</th>
                        <th>Assigned to</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks" v-bind:key="task.id">
                        <td><router-link :to="`/task/${task.id}`">{{task.name}}</router-link></td>
                        <td>{{task.user.name}}</td>
                        <td>
                            <span class="badge badge-primary mr-1" v-for="assignee in task.users" v-bind:key="assignee.id">{{assignee.name}}</span>
                        </td>
                        <td>
                            {{task.status !== null ? task.status.name : 'N/A'}}
                        </td>
                        <td>
                           <router-link :to="`/task/${task.id}/edit`" class="btn btn-info" v-if="user.id === task.user.id">Edit</router-link>
                           <router-link :to="`/task/${task.id}`" v-if="task.users.some(el => el.id === user.id)" class="btn btn-info">Change Status</router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
              <div class="spinner-grow text-primary mx-auto my-4 d-block" role="status" v-if="!this.complete">
                    <span class="sr-only">Loading...</span>
              </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            tasks: null,
            complete: false,
            user: null,
            search: ''
        };
    },
    created() {
        const userInfo = localStorage.getItem("user");
        let self = this;
        const userData = JSON.parse(userInfo);
        this.user = userData.user;
        axios
            .get("/api/task", {
                headers: {
                    Authorization: `Bearer ${userData.token}`
                }
            })
            .then(res => {
                this.tasks = res.data.tasks;
                this.complete = true;
            })
            .catch(err => {
                console.log(err)
            });
    },
    methods:{
        searchResults(){
            this.tasks = null;
            this.complete = false;
            const userInfo = localStorage.getItem("user");
            const userData = JSON.parse(userInfo);
            this.user = userData.user;
             axios
                .put('/api/task/search',
                    {
                        user: userData,
                        keyword: this.search,
                        _token: this.csrf
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${userData.token}`
                        }
                    }
                )
                .then(res => {
                    this.tasks = res.data.tasks;
                    this.complete = true;
                })
                .catch(err => {
                    this.flash(err, "error");
                });
        }
    }
};
</script>

<template>
  <div class="container bg-white shadow-lg mt-4 card" v-if="task">
    <h1 class="mt-4">{{ this.task.name }}</h1>
    <hr />
    <div class="card-body" v-html="this.task.description"></div>
    <div class="card-footer row flex-column">
      <div class="row">
        <div class="col">Created By: {{ this.task.user.name }}</div>
        <div class="col d-flex justify-content-end">
          Assignees:
          <div
            class="badge bg-primary rounded text-white mx-1 px-2 assignee-badge"
            v-for="user in this.task.users"
            v-bind:key="user.id"
          >{{ user.name }}</div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          Status:
          <select
            class="form-control"
            v-model="st"
            v-if="this.task.users.some(el => el.id === this.user.id)"
            @change="setStatus"
          >
            <option
              v-for="status in statuses"
              v-bind:key="status.id"
              :selected="status.id === ( task.status == null ? 0 : task.status.id)"
              :value="status.id"
            >{{ status.name }}</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      task: null,
      statuses: null,
      user: null,
      userData:null,
      st: null
    };
  },
  created() {
    const userInfo = localStorage.getItem("user");
    const userData = JSON.parse(userInfo);
    this.user = userData.user;
    this.userData = userData;

    axios.get(`/api/task/${this.$route.params.id}`).then(response => {
      this.task = response.data.task;
    });
    axios.get(`/api/statuses`).then(response => {
      console.log(response.data.statuses);
      this.statuses = response.data.statuses;
    });
  },
  methods: {
    setStatus() {
      axios
        .put(
          `/api/setStatus/${this.task.id}`,
          { status_id: this.st, user: this.userData },
          {
            headers: {
              Authorization: `Bearer ${this.userData.token}`
            }
          }
        )
        .then(
             this.flash("Status successfully changed!", "success")
        )
        .catch(err=>{
            this.flash(err, "error");
        });
    }
  }
};
</script>

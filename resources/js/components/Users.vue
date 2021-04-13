<template>
  <div>
    <form @submit.prevent="addUser(user)" class="mb-3">
      <div v-if="error.length !== 0">
        <p class="alert alert-danger" role="alert">{{ error.message }}</p>
      </div>

      <div class="form-group">
        <input
          type="text"
          class="form-control"
          placeholder="First name"
          v-model="user.firstname"
        />
        <div v-if="error.data && error.data.firstname">
          <p class="alert alert-danger" role="alert">
            {{ error.data.firstname[0] }}
          </p>
        </div>
      </div>
      <div class="form-group">
        <input
          type="text"
          class="form-control"
          placeholder="Surname"
          v-model="user.surname"
        />
        <div v-if="error.data && error.data.surname">
          <p class="alert alert-danger" role="alert">
            {{ error.data.surname[0] }}
          </p>
        </div>
      </div>
      <div class="form-group">
        <input
          type="date"
          class="form-control"
          placeholder="Date of birth"
          v-model="user.dob"
        />
        <div v-if="error.data && error.data.dob">
          <p class="alert alert-danger" role="alert">
            {{ error.data.dob[0] }}
          </p>
        </div>
      </div>
      <div class="form-group">
        <input
          type="text"
          class="form-control"
          placeholder="Phone number"
          v-model="user.phone"
        />
        <div v-if="error.data && error.data.phone">
          <p class="alert alert-danger" role="alert">
            {{ error.data.phone[0] }}
          </p>
        </div>
      </div>
      <div class="form-group">
        <input
          type="text"
          class="form-control"
          placeholder="Email"
          v-model="user.email"
        />
        <div v-if="error.data && error.data.email">
          <p class="alert alert-danger" role="alert">
            {{ error.data.email[0] }}
          </p>
        </div>
      </div>
      <button type="submit" class="btn btn-light btn-block">Save</button>
    </form>
    <div class="users-container">
      <div
        class="card card-body m-2 user"
        v-for="user in users"
        v-bind:key="user.id"
      >
        <p class="card-title">
          Name: {{ user.firstname + " " + user.surname }}
        </p>
        <p class="card-text">Date of birth: {{ user.dob }}</p>
        <p class="card-text">Phone number: {{ user.phone }}</p>
        <p class="card-text">Email: {{ user.email }}</p>
        <button @click="editUser(user)" class="btn btn-warning mb-2">
          Edit
        </button>
        <button @click="deleteUser(user.id)" class="btn btn-danger">
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                user: {
                    firstname: '',
                    surname: '',
                    dob: '',
                    phone: '',
                    email: '',
                },
                user_id: '',
                edit: false,
                loginData: {
                    email: 'api@app.com',
                    password: 'test1234'
                },
                error: [],
            }
        },
        created() {
            this.login()
        },

        methods: {
            login() {

                axios.get('/sanctum/csrf-cookie').then(response => 
                {
                    axios.post('api/login', this.loginData)
                        .then(response => {

                            let token = response.data.token
                            localStorage.setItem("token", token)
                            this.fetchUsers()
                        })
                        .catch(function (error) {

                            if (error.response) {
                                const errorMessage = error.response.data.error.message;
                                return Promise.reject(errorMessage);
                            } else {
                                const errorMessage = 'Connection with server problem!';
                                return Promise.reject(errorMessage);
                            }
                        });
                    }
                    )
            },
            fetchUsers() {

                let token = localStorage.getItem("token")
                let AuthStr = "Bearer ".concat(token)

                axios.get('api/users', {
                     headers: { Authorization: AuthStr },
                }).then(response => {
                    this.users = response.data.data
                })
            },
            deleteUser(id) {
                if(confirm('Are you sure?')) {
                    let token = localStorage.getItem("token")
                    let AuthStr = "Bearer ".concat(token)
                     axios.delete('api/users/' + id, {
                        headers: { Authorization: AuthStr },
                        })
                        .then(response => {
                            this.fetchUsers()
                            this.error = {message:"User deleted!"}
                        })
                        .catch(function(error) {
                        if (error.response) {
                            const errorData = error.response.data;
                            console.log(errorData)
                        } else {
                            const errorMessage = "Connection with server problem!"
                            console.log(errorMessage)
                        }
                    });
                }
            },
            addUser(data) {
                let token = localStorage.getItem("token")
                let AuthStr = "Bearer ".concat(token)
                if(!this.edit) {
                    axios
                        .post("api/users", data, {
                        headers: { Authorization: AuthStr },
                        }).then(response => {
                                this.fetchUsers()
                                this.clearForm()                            
                        })
                        .catch(error => {
                            if (error.response) {
                                const errorData = error.response.data
                                let validationErrors = errorData
                                this.error = validationErrors
                            } else {
                                const errorMessage = "Connection with server problem!"
                                console.log(errorMessage)
                            }
                        })
                } else {
                    axios
                        .put("api/users/" + this.user_id, data, {
                        headers: { Authorization: AuthStr },
                        })
                        .then(response => {
                            this.fetchUsers()
                            this.edit = false
                            this.clearForm()
                        })
                        .catch(error => {
                            if (error.response) {
                                const errorData = error.response.data
                                let validationErrors = errorData
                                this.error = validationErrors
                            } else {
                                const errorMessage = "Connection with server problem!"
                                console.log(errorMessage)
                            }
                        })
                }
            },
            editUser(user) {
                this.edit = true
                this.user_id = user.id
                this.user.firstname = user.firstname
                this.user.surname = user.surname
                this.user.dob = user.dob
                this.user.phone = user.phone
                this.user.email = user.email
            },
            clearForm() {
                this.user_id = ''
                this.user.firstname = ''
                this.user.surname = ''
                this.user.dob = ''
                this.user.phone = ''
                this.user.email = ''
                this.error = []
            }
        }
    }

</script>


<style scoped>
.users-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  flex-wrap: wrap;
}

.user {
  width: 20%;
}
</style>
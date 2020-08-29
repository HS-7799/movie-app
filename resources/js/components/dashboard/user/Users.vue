<template>
    <div class="row">
        <div class="card col-12">
            <div class="row">
                <div class="col-md-7 col-sm-5">
                    <input type="text" v-model="filterText"  class="form-control" placeholder="Search an user by name,email...">
                </div>
                <div class="col-md-3 col-sm-3">
                    <select v-model="filterRole"  class="form-control">
                        <option :value="role.label" v-for="role in roles" :key="role.id" >{{ role.label }}</option>
                        <option value="" selected  >All roles</option>
                    </select>

                </div>
                <div  class="col-md-2 col-sm-4" v-if="$auth.can('create_user')" >
                    <router-link to="/dashboard/users/create" tag="button" class="btn btn-primary">Add</router-link>
                </div>
            </div>
            <div v-if="showUsers" >
                <table v-if="users.length > 0" class="mt-1 table table-light table-bordered" >
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user,index) in filteredUsers" :key="user.id" >
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td v-if="user.roles.length > 0" >
                                <span v-for="role in user.roles" :key="role.id"
                                    class="p-2 mb-1 mr-1 badge badge-info"
                                >{{ role }}
                                </span>
                            </td>
                            <td v-else >Simple user</td>

                            <td v-if="$auth.can('update_user') || $auth.can('delete_user')" >
                                <router-link
                                        v-if="$auth.can('update_user')"
                                        tag="button"
                                        :to="{name : 'Edit user' ,params : {id : user.id}}"

                                        class="btn btn-warning"
                                        title="update user"
                                >
                                    <i class="fas fa-edit"></i>
                                </router-link>
                                <button v-if="$auth.can('delete_user')"
                                        class="btn btn-danger"
                                        title="delete"
                                        @click="startDelete(user.id,index)"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            <td v-else>No actions</td>

                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center" >
                    No users
                </div>
                <div class="row justify-content-center" >
                    <sliding-pagination
                    :current="current_page"
                    :total="total"
                    @page-change="loadPage"
                ></sliding-pagination>
                </div>
            </div>
            <div v-else class="row justify-content-center">
                <div class="spinner-border text-primary"></div>
            </div>
      </div>
    </div>
</template>

<script>
export default {
    data()
    {
        return {
            filterText : '',
            filterRole : '',
            users : [],
            roles : [],
            current_page : 1,
            total : 0,
            showUsers : false
        }
    },
    computed : {
        filteredUsers()
        {
            return this.users.filter((user) => {
                var a = (this.filterRole === '' ? true : user.roles.includes(this.filterRole))
                 var b = (user.name.toLowerCase().match(this.filterText.toLowerCase())) || (user.email.toLowerCase().match(this.filterText.toLowerCase()))
                return a && b


            })
        }
    },
    methods : {
        startDelete(id,index)
        {
            this.$swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.value) {
                this.deleteUser(id,index)
            }
            })
        },
        deleteUser(id,index)
        {
            axios.delete(`/users/${id}`)
            .then((result) => {

                 this.successMessage('User has been deleted');
                 this.users.splice(index,1);
                 if(this.users.length === 0 && this.current_page > 1)
                 {
                     this.current_page -= 1
                 }
                 this.loadPage(this.current_page)
            }).catch((err) => {
                console.log(err)
                this.handle(err)
            });
        },
        successMessage(msg)
        {
            this.$swal.fire({
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 1000
            })
        },
        loadPage(page)
        {
                axios.get(`/users?page=${page}`)
                .then((res) => {
                    this.showUsers = true
                    this.current_page = page
                    this.total = res.data.meta.last_page
                    this.users = res.data.data
                }).catch((err) => {
                    console.log(err.response.data)
                    this.handle(err)
                });
        },
        handle(err)
        {
            if(err.response.status === 401)
            {
                window.location = '/login'
            }
            if(err.response.status === 403)
            {
                this.$emit('actionUnauthorized',true)
            }
        },
    },
    created()
    {
        if(this.$auth.can('manage_user'))
        {
            this.loadPage(this.current_page)
            axios.get('/roles')
            .then((res) => {
                this.roles = res.data
            }).catch((err) => {
                this.handle(err)
            });
        }
        else
        {
            this.$router.push('/dashboard/home')
        }
    },


}
</script>

<style>

</style>

<template>
    <div class="row">
        <div class="card col-12">
            <div class="row">
                <div class="col-md-7 col-sm-5">
                    <input type="text" v-model="filterText" class="form-control" placeholder="Search a role...">
                </div>
                <div class="col-md-3 col-sm-4">
                    <select class="form-control" v-model="filterAbility" >
                        <option v-for="ability in abilities" :key="ability.id" :value="ability.label" >
                            {{ ability.label }}
                        </option>
                        <option value="" selected >All abilities</option>
                    </select>
                </div>
                <div v-if="$auth.can('create_role')" class="col-md-2 col-sm-3">
                    <router-link tag="button" to="roles/create" class="btn btn-primary">Add</router-link>
                </div>
            </div>
            <table v-if="roles.length > 0" class="mt-1 table table-light table-bordered " >
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Abilities</th>
                        <th>User count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(role,index) in filteredRoles" :key="role.id" >
                        <td>{{ role.label }}</td>
                        <td v-if="role.abilities.length > 0" >
                            <span v-for="ability in role.abilities"
                                  :key="ability.id"
                                   class="p-2 mb-1 mr-1 badge badge-info"
                            >{{ ability.label }}</span>
                        </td>
                        <td v-else >No abilities</td>
                        <td> {{ role.user_count }} </td>
                        <td v-if="$auth.can('delete_role') || $auth.can('update_role')" >
                            <router-link
                                    tag="button"
                                    :to="{ name : 'Edit role', params : {id : role.id} }"
                                    v-if="$auth.can('update_role')"
                                    class="mb-1 btn btn-warning"
                                    title="update role"
                            >
                                <i class="fas fa-edit"></i>
                            </router-link>
                            <button v-if="$auth.can('delete_role')" class="btn btn-danger"
                                    title="delete"
                                    @click="startDelete(role.id,index)"
                            >
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                        <td v-else >No actions</td>
                    </tr>
                </tbody>
            </table>
            <div v-else class="row justify-content-center">
                <div class="spinner-border text-primary"></div>

            </div>
      </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            roles : [],
            abilities : [],
            filterText : '',
            filterAbility : '',
        }
    },
    methods : {
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
            if(err.response.status === 404)
            {
                this.$router.push({name : 'Not found'})
            }
        },
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
                this.deleteRole(id,index)
            }
            })
        },
        deleteRole(id,index)
        {
            axios.delete(`/roles/${id}`)
            .then((result) => {
                 this.successMessage('Role has been deleted');
                 this.roles.splice(index,1);
            }).catch((err) => {
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
        }
    },
    created()
    {

        if(this.$auth.can('manage_role'))
        {
            axios.get('/roles')
            .then((res) => {
                this.roles = res.data
            }).catch((err) => {
                this.handle(err)
            });
            axios.get('/abilities')
            .then((res) => {
                this.abilities = res.data.data
            }).catch((err) => {
                this.handle(err)
            });
        }
        else
        {
            this.$router.push('/dashboard/home');
        }

    },
    computed : {
        filteredRoles()
        {
            return this.roles.filter((role) => {
                const abilities = role.abilities.map((ability)=> {
                    return ability.label
                })
                abilities.push('')
                return (role.label.toLowerCase().match(this.filterText.toLowerCase())) && abilities.includes(this.filterAbility)
            })
        }
    }


}
</script>

<style>

</style>


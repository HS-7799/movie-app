<template>
  <div class="row">
      <div class="col-12">
          <div class="mb-1 float-right">
              <button class="btn btn-primary" @click="$router.push('/dashboard/users')" >back</button>
          </div>
          <form @submit.prevent="submitForm" >
              <div class="form-group">
                  <input type="text" v-model="form.name" placeholder="name" class="form-control">
                  <p v-if="errors.name" class="text-danger">{{ errors.name[0] }}</p>
              </div>
              <div class="form-group">
                  <input type="email" v-model="form.email" placeholder="email" class="form-control">
                  <p v-if="errors.email" class="text-danger">{{ errors.email[0] }}</p>
              </div>
              <div class="form-group">
                  <input type="password" v-model="form.password" placeholder="password" class="form-control">
                  <p v-if="errors.password" class="text-danger">{{ errors.password[0] }}</p>
              </div>
              <div class="form-group">
                  <input type="password" v-model="form.password_confirmation" placeholder="password confirmation" class="form-control">
              </div>
              <div class="form-group">
                  <select class="form-control" multiple v-model="form.roles"  >
                      <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.label }}</option>
                  </select>
                  <p v-if="errors.roles" class="text-danger">{{ errors.roles[0] }}</p>

              </div>
              <div class="form-group">
                  <button type="submit" class="btn btn-primary">Add</button>
              </div>
          </form>
      </div>
  </div>
</template>

<script>
export default {
    data()
    {
        return {
            form : {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
                roles : []
            },
            errors : {},
            roles : []

        }
    },
    methods : {
        handle(err)
        {
            if(err.response.data.errors)
            {
                this.errors = err.response.data.errors
            }
            if(err.response.status === 401)
            {
                window.location = '/login'
            }
            if(err.response.status === 403)
            {
                this.$emit('actionUnauthorized',true)
            }
        },
        submitForm()
        {
            axios.post('/users',this.form)
            .then((res) => {
                this.$router.push('/dashboard/users');
            }).catch((err) => {
                console.log(err.response.data)
                this.handle(err)
            });
        },
        loadRoles()
        {
            axios.get('/roles')
            .then((res) => {
                this.roles = res.data
            }).catch((err) => {
                console.log(err.response.data)
                this.handle(err)
            });
        }
    },
    created()
    {
        this.$auth.can('create_user') ? this.loadRoles() : this.$router.push('/dashboard/home');
    }

}
</script>

<style>

</style>

<template>
  <div class="row" >
      <div class="col-12">
          <button class="btn btn-primary" @click="back" >Back</button>
          <form @submit.prevent="submitForm" >
            <div class="form-group">
                <label for="name">Role name</label>
                <input type="text" id="name"  v-model="form.name" class="form-control">
                <p class="text-danger" v-if="errors.name" >
                        {{ errors.name[0] }}
                    </p>
            </div>
            <div class="form-group">
                <div class="form-check-inline" v-for="(ability) in abilities" :key="ability.label" >
                    <label class="form-check-label">
                        <input type="checkbox" v-model="form.abilities" :checked="true" class="form-check-input" :value="ability.id">{{ ability.label }}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" >
                    Update
                </button>
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
            role : {},
            abilities : [],
            form : {
                name : null,
                abilities : []
            },
            errors : {}
        }
    },
    methods : {
        back()
        {
            this.$router.push('/dashboard/roles')
        },
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
            if(err.response.status === 404)
            {
                this.$router.push({name : 'Not found'})
            }
        },
        submitForm()
        {

                axios.patch(`/roles/${this.role.id}`,this.form)
                .then((res) => {
                    if(this.$auth.hasRole(this.role.name))
                    {
                        window.location = '/dashboard/roles';
                    }
                    this.$router.push('/dashboard/roles');
                }).catch((err) => {
                    console.log(err.response.data)
                    this.handle(err)
                });

        }
    },
    created()
    {
        if(this.$auth.can('update_role'))
        {
            axios.get(`/roles/${this.$route.params.id}`)
            .then((res) => {
                this.role = res.data
                this.form.name = this.role.name
                this.form.abilities = this.role.abilities.map((ability) => {
                    return ability.id
                })
            }).catch((err) => {
                console.log(err)
                this.handle(err)
            });
            axios.get('/abilities')
                .then((res) => {
                    this.abilities = res.data.data
                }).catch((err) => {
                    console.log(err)
                    this.handle(err)
            });
        }
        else
        {
            this.$router.push('/dashboard/home');
        }
    }

}
</script>

<style>

</style>

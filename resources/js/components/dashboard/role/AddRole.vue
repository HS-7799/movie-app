use App\Http\Resources\ability\AbilityResource;
<template>
  <div class="row">
      <div class="col-12">
          <div class="mb-1 float-right">
              <button class="btn btn-primary" @click="$router.push('/dashboard/roles')" >back</button>
          </div>
          <form @submit.prevent="submitForm" >
            <div class="form-group">
                <input type="text"   v-model="form.name" placeholder="Role name" class="form-control">
                <p class="text-danger" v-if="errors.name" >
                        {{ errors.name[0] }}
                    </p>
            </div>
            <div class="form-group">
                <div class="form-check-inline" v-for="(ability) in abilities" :key="ability.label" >
                    <label class="form-check-label">
                        <input type="checkbox" v-model="form.abilities" class="form-check-input" :value="ability.id">{{ ability.label }}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" >
                    Add
                </button>
            </div>
      </form>
      <a href="" @click.prevent="ability = !ability" >
          {{ ability ? 'cancel' : 'add ability'  }}
      </a>
      <div v-if="ability" >
      <input type="text" required placeholder="Ability name ex : create_user"  class="form-control" v-model="abilityName" >
      <button class="mt-1 btn btn-primary" @click="addAbility" >Add ability</button>

      </div>
      </div>
  </div>
</template>

<script>
export default {

    data()
    {
        return {
            form : {
                name : null,
                abilities : []
            },
            errors : {},
            abilities : {},
            abilityName : '',
            ability : false
        }
    },
    methods : {

        addAbility()
        {

            axios.post('/abilities',{
                name : this.abilityName
            })
            .then((res) => {
                console.log(res)
                this.abilityName = ''
                this.abilities.push(res.data)
            }).catch((err) => {
                console.log(err)
                this.handle(err)
            });
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
            axios.post('/roles',this.form)
            .then((res) => {
                console.log(res.data)
                this.$router.push('/dashboard/roles')
            }).catch((err) => {
                console.log(err.response.data)
                this.handle(err)
            });
        }
    },
    created()
    {
        if(this.$auth.can('create_role'))
        {
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




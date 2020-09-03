<template>
    <div class="form-group">
        <div>
            <img :src="url" alt="actor picture" />
        </div>
        <label for="photo">Actor photo</label>
        <input id="photo" class="form-control-file" type="file" @change="updatePhoto" >
        <p class="text-danger" v-if="errors.photo" >
            {{ errors.photo[0] }}
        </p>
    </div>
</template>

<script>
export default {

    props : ['photo'],

    data()
    {
        return {
            errors : {},
            url : this.photo ? this.photo : '/images/noImage.png'
        }
    },

    methods : {
        updatePhoto($event)
        {
            const form = new FormData();

            form.append('photo',$event.target.files[0])

            axios.post(`/actors/${this.$route.params.slug}/photo`,form)
            .then((res) => {
                this.url = res.data
            }).catch((err) => {
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
                    alert('action Unauthorized');
                }
                if(err.response.status === 404)
                {
                    this.$router.push({name : 'Not found'})
                }
            });
        }
    }

}
</script>

<style scoped>

</style>

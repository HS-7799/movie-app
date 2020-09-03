<template>
    <div class="movie-poster">
        <img :src="url" width="100%" alt="Movie poster" />
        <div  >
            <label for="poster" >Movie poster</label>
            <input id="poster"  class="form-control-file" type="file" ref="poster" @change="uploadPoster" >
            <p class="text-danger" v-if="errors.poster" >
                {{ errors.poster[0] }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props : ['poster'],
    data()
    {
        return {
            errors : {},
            url : this.poster ? this.poster : '/images/noImage.png'
        };
    },
    methods : {
        uploadPoster()
        {
            const form = new FormData();

            form.append('poster',this.$refs.poster.files[0]);

            axios.post(`/movies/${this.$route.params.id}/poster`,form)
            .then((res) => {
                this.url = res.data
                this.errors = {}
            })
            .catch((err) => {
                this.handle(err)
            })
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
                alert('This action is unauthorized')
            }
            if(err.response.status === 404)
            {
                this.$router.push({name : 'Not found'})
            }
        }
    }

}
</script>

<style scoped>

.movie-poster
{
    cursor:pointer;
    width : 200px
}


</style>


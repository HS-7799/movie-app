<template>
    <div>
        <button class="btn" @click="toggleFavorite" :class="{'disabled' : waitForResponse}"  >
            {{ movieIsFavorite ? 'Remove from favourites':'Add to favourites' }}
            <i class="fas fa-heart" ></i>
        </button>
    </div>
</template>

<script>


export default {

    props : ['movieId','isFavorite'],
    data()
    {
        return {
            movieIsFavorite : this.isFavorite,
            waitForResponse : false
        }
    },
    methods : {
        toggleFavorite()
        {
            if(!this.waitForResponse)
            {
                this.waitForResponse = true
                axios.post(`/movies/${this.movieId}/favourite`)
                .then((res) => {
                    this.waitForResponse = false
                    this.movieIsFavorite = !this.movieIsFavorite;
                }).catch((err) => {
                    this.waitForResponse = false
                    if(err.response.status === 401)
                    {
                        this.$swal.fire({
                        // title: 'Are you sure?',
                        text: "You need to login",
                        // icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Login'
                        }).then((result) => {
                            if (result.value) {
                                window.location = '/login';
                            }
                        })
                    }
                });
            }
        }
    }
    
}

</script>

<style scoped>
button
{
    border-radius : 0;
    background-color : white;
    color : #4bb8ce;
    font-weight : bold
}

div
{
    margin-bottom : 7px;
}

</style>
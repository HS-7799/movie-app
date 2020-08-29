<template>
    <div>
        <span class="vote mx-1" @click="vote('up')" >
            <i class="fas fa-thumbs-up icon" :class="{'text-primary' : isLiked}" ></i>
            <span class="voteCount" > {{ likeCount }} </span>
        </span>
        <span class="vote mx-1" @click="vote('down')" >
            <i class="fas fa-thumbs-down icon" :class="{'text-primary' : isUnLiked}" ></i>
            <span class="voteCount" > {{ unlikeCount }} </span>
        </span>
    </div>
</template>

<script>
export default {

    props : ['entityId','votes'],
    data()
    {
        return {
            likeCount : 0,
            unlikeCount : 0,
            isLiked : false,
            isUnLiked : false,
            waitForResponse : false
        }
    },
    methods : {
        vote(type)
        {
            if(!this.waitForResponse)
            {
                this.waitForResponse = true
                if(type === 'up')
                {
                    if(this.isLiked)
                    {
                        this.deleteVote(type)

                    } else {
                        this.createOrUpdateVote(type)
                    }

                } else {
                    if(this.isUnLiked)
                    {
                        this.deleteVote(type)
                    } else{
                        this.createOrUpdateVote(type)
                    }
                }
            }
        },
        deleteVote(type)
        {

            axios.delete(`/movies/${this.entityId}/vote`)
            .then((res) => {
                this.waitForResponse = false
                if(type === 'up')
                {
                    this.isLiked = false
                    this.likeCount--;
                } else {
                    this.isUnLiked = false
                    this.unlikeCount--;
                }
            }).catch((err) => {
                console.log(err.response.data);
            });
        },
        createOrUpdateVote(type)
        {
            const form = new FormData();
            form.append('type',type)
            axios.post(`/movies/${this.entityId}/vote`,form)
            .then((response) => {
                this.waitForResponse = false
                if(response.status === 201)
                {
                    if(type === 'up')
                    {
                        this.isLiked = true
                        this.likeCount++;
                    } else {
                        this.isUnLiked = true
                        this.unlikeCount++;
                    }
                } else if(response.status === 200){

                    if(type === 'up')
                    {
                        this.isUnLiked = false
                        this.unlikeCount--;
                        this.isLiked = true
                        this.likeCount++;
                    } else {
                        this.isLiked = false
                        this.likeCount--;
                        this.isUnLiked = true
                        this.unlikeCount++;
                    }

                }
            }).catch((err) => {
                this.handle(err)
            });
        },
        handle(err)
        {
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
        }
    },
    created()
    {
        this.votes.forEach(vote => {

            if(vote.type === 'up')
            {
                this.likeCount++;

                if(vote.user_id === this.$auth.id())
                {
                    this.isLiked = true
                }
            } else {
                this.unlikeCount++;
                if(vote.user_id === this.$auth.id())
                {
                    this.isUnLiked = true
                }
            }
        });


    }

}
</script>


<style>

.icon
{
    font-size : 20px;
    color : gray
}
.vote
{
    cursor:pointer;
}

.voteCount
{
    font-size : 16px;
    padding : 2px
}

.icon:hover
{
    transform : scale(1.5)
}

</style>

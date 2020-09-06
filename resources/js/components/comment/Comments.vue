<template>
    <div class="col-md-8 col-10 bg-light">

        <app-add-comment @newComment="addComment($event)" :movieId="movieId" ></app-add-comment>
        <div class="rounded " v-for="(comment,index) in comments.data" :key="comment.id" >
            <app-single-comment
                :movieId="movieId"
                 :comment="comment"
                  @commentDeleted="spliceComment(index)"
                >
            </app-single-comment>
        </div>
        <p v-if="comments.data.length === 0" >Be the first who comment !!!</p>
        <div v-if="comments.next_page_url" class="text-primary text-center" >
            <a href="" @click.prevent="fetchComments" >See more comments</a>
        </div>
    </div>
</template>


<script>
import SingleComment from './SingleComment.vue';
import AddComment from './AddComment.vue';
export default {
    props : ['movieId'],
    data()
    {
        return {
            comments : {
                data : []
            }

        }
    },
    methods : {
        spliceComment(index)
        {
            this.comments.data.splice(index,1)
        },
        addComment(comment)
        {
            this.comments.data.push(comment);
        },
        fetchComments()
        {
            const url = this.comments.next_page_url ? this.comments.next_page_url : `/comments/${this.movieId}`
            axios.get(url)
            .then((res) => {
                this.comments = {
                    ...res.data,
                    data : [
                        ...this.comments.data,
                        ...res.data.data
                    ]
                }
            })
        }
    },
    components : {
        appSingleComment : SingleComment,
        appAddComment : AddComment
    },
    created()
    {
        this.fetchComments();
    }

}
</script>

<style scoped >

</style>

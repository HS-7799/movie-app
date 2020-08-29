<template>
    <div class="col-lg-10 col-12 bg-white">

        <app-add-comment @newComment="addComment($event)" :movieId="movieId" ></app-add-comment>
        <div class="rounded " v-for="(comment,index) in comments.data" :key="comment.id" >
            <app-single-comment
                :movieId="movieId"
                 :comment="comment"
                  @commentDeleted="spliceComment(index)"
                >
            </app-single-comment>
        </div>
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
            }).catch((err) => {
                console.log(err.response.data);
            });
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

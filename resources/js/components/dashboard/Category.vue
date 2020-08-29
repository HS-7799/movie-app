<template>
  <div class="row">

        <div class="col-12">
          <form @submit.prevent="submitForm" class="row" >
              <div class="col-7">
                      <input type="text" id="name" v-model="form.name" class="form-control" placeholder="Category name" >
              </div>
                  <div class="col-5" >
                        <button type="submit" class="btn btn-primary">
                        {{ editing ? 'Update' : 'Add' }}
                        </button>
                        <button v-if="editing" @click="cancelEditing" class="btn btn-danger">Cancel</button>
                  </div>

          </form>
                <p class="text-danger" v-if="errors.name" >
                    {{ errors.name[0] }}
                </p>
        </div>
        <div class="card col-12">
            <input type="text" class="form-control" v-model="filterText" placeholder="Search a category...">
            <div v-if="showCategories" >
                <table v-if="categories.length > 0" class="mt-1 table table-light table-bordered"  >
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Movies count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category,index) in filteredCategories" :key="category.id" >
                            <td>{{ category.name  }}</td>
                            <td>{{ category.movies_count }}</td>
                            <td v-if="$auth.can('update_category') || $auth.can('delete_category')" >
                                <button v-if="$auth.can('update_category')"
                                        class="btn btn-warning"
                                        title="update category"
                                        @click="startEditing(category,index)"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button v-if="$auth.can('delete_category')"
                                        class="btn btn-danger"
                                        title="delete"
                                        @click="startDelete(category.slug,index)"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            <td v-else >No actions</td>
                        </tr>
                    </tbody>
                </table>
                <div v-else >No categories</div>
                <div class="row justify-content-center" >
                    <sliding-pagination
                    :current="current_page"
                    :total="total"
                    @page-change="loadPage"
                ></sliding-pagination>
                </div>
            </div>


            <div v-else class="mt-1 row justify-content-center">
                <div class="spinner-border text-primary"></div>
            </div>

      </div>
  </div>
</template>

<script>

export default {

    data() {
        return {
            form : {
                name : null
            },
            errors : {},
            categories : [],
            editing : false,
            editSlug : null,
            editIndex : null,
            noAccess : false,
            filterText : '',
            current_page : 1,
            total : 0,
            showCategories : false
        }
    },
    methods : {
        startDelete(slug,index)
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
                this.deleteCategory(slug,index)
            }
            })
        },
        submitForm()
        {
            this.editing ? this.updateCategory() : this.addCategory();
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
        },
        addCategory()
        {
            axios.post('/categories',this.form)
            .then((res) => {
                this.successMessage('Category added successfully'),
                this.categories.unshift(res.data)
                this.errors = {}
                this.form.name = null
            }).catch((err) => {
                console.log(err.response.data)
                this.handle(err)
            });
        },
        deleteCategory(slug,index)
        {
            axios.delete(`/categories/${slug}`)
            .then((res) => {
                this.successMessage('Category has been deleted')
                this.categories.splice(index,1);
                if(this.categories.length === 0 && this.current_page > 1 )
                 {
                     this.current_page -= 1
                 }
                 this.loadPage(this.current_page)

            }).catch((err) => {
                console.log(err.response)
                this.handle(err)
            });

        },
        updateCategory()
        {

            axios.put('/categories/'+this.editSlug,this.form)
            .then((res) => {
                this.successMessage('Category updated successfully')
                this.categories.splice(this.editIndex,1,res.data)
                this.cancelEditing();
                this.errors = {}
            }).catch((err) => {
                console.log(err.response.data)
                this.handle(err)
            });


        },
        startEditing(category,index)
        {
            window.scroll(0,0);
            this.editing = true;
            this.editSlug = category.slug
            this.editIndex = index;
            this.form.name = category.name;
        },
        cancelEditing()
        {
            this.editing = false;
            this.editIndex = null;
            this.editSlug = null
            this.form.name = null
        },
        successMessage(msg)
        {
            this.$swal.fire({
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 1000
            })
        },
        loadPage(page)
        {
            axios.get(`/categories?page=${page}`)
            .then((res) => {
                this.showCategories = true
                this.current_page = page
                this.total = res.data.meta.last_page
                this.categories = res.data.data
            }).catch((err) => {
                console.log(err.response.data)
                this.handle(err)
            });
        },
    },
    computed : {
        filteredCategories() {
            return this.categories.filter((element) => {
                return element.name.toLowerCase().match(this.filterText.toLowerCase())
            });
        }
    },
    created()
    {
        if(this.$auth.can('manage_category'))
        {
            this.loadPage(this.current_page)
        }
        else
        {
            this.$router.push('/dashboard/home');
        }
    },


}
</script>


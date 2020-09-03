import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from '../components/dashboard/Home.vue'
import Category from '../components/dashboard/Category.vue'
import Profile from '../components/dashboard/Profile.vue'
import NotFound from '../components/dashboard/NotFound.vue'
import Roles from '../components/dashboard/role/Roles.vue'
import AddRole from '../components/dashboard/role/AddRole.vue'
import EditRole from '../components/dashboard/role/EditRole.vue'
import Movies from '../components/dashboard/movie/Movies.vue'
import UploadMovie from '../components/dashboard/movie/UploadMovie.vue'
import EditMovie from '../components/dashboard/movie/EditMovie.vue'
import EditCast from '../components/dashboard/movie/EditCast.vue'
import Users from '../components/dashboard/user/Users.vue'
import AddUser from '../components/dashboard/user/AddUser.vue'
import EditUser from '../components/dashboard/user/EditUser.vue'
import Actors from '../components/dashboard/actor/Actors.vue'
import AddActor from '../components/dashboard/actor/AddActor.vue'
import EditActor from '../components/dashboard/actor/EditActor.vue'

const routes = [
    { path: '/dashboard/home', component: Home ,name : 'Dashboard' },
    { path: '/dashboard/categories', component: Category, name : 'Categories' },
    { path: '/dashboard/profile', component: Profile, name : 'Profile' },
    { path: '/dashboard/roles', component: Roles, name : 'Roles' },
    {path : '/dashboard/roles/create', name : 'Create role', component : AddRole},
    {path : '/dashboard/roles/:id/edit', name : 'Edit role', component : EditRole},
    { path: '/dashboard/movies', component: Movies, name : 'Movies' },
    { path: '/dashboard/movies/upload', component: UploadMovie, name : 'Upload Movie' },
    { path: '/dashboard/movies/:id/edit', component: EditMovie, name : 'Edit Movie' },
    { path: '/dashboard/movies/:id/edit/cast', component: EditCast, name : 'Edit Cast' },
    { path: '/dashboard/users', component: Users, name : 'Users' },
    { path: '/dashboard/users/create', component: AddUser, name : 'Create user' },
    { path: '/dashboard/users/:id/edit', component: EditUser, name : 'Edit user' },
    { path: '/dashboard/actors', component: Actors, name : 'Actors' },
    { path: '/dashboard/actors/create', component: AddActor, name : 'Create Actor' },
    { path: '/dashboard/actors/:slug/edit', component: EditActor, name : 'Edit Actor' },
    { path: '*', component: NotFound, name : 'Not found' },


]

const router = new VueRouter({
    routes,
    mode : 'history',
})

export default router;



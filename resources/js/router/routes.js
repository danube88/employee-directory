import Vue from 'vue';
import VueRouter from 'vue-router'
import {store} from '../vuex/store'

Vue.use(VueRouter);

const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next();
        return
    }
    next('/home')
}

const ifAuthenticated = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        next();
        return
    }
    next('/hierarchy')
}

import Login from './../components/auth/login'
import Register from './../components/auth/register'
import PasswordEmail from '../components/auth/password/email'
import PasswordReset from '../components/auth/password/reset'

import CreateEmployee from './../components/CreateEmployeeComponent.vue';
import EditEmployee from './../components/EditEmployeeComponent.vue';

import AppComponent from './../components/AppComponent.vue';
import ListComponent from './../components/ListComponent.vue';
import { library } from '@fortawesome/fontawesome-svg-core'
import { faFolderPlus,faFolderMinus,faFolder } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
library.add(faFolderPlus)
library.add(faFolderMinus)
library.add(faFolder)

Vue.component('font-awesome-icon', FontAwesomeIcon);

import HomeLayout from './../components/layout/layout.vue';

const routes = [
      {
        path: '/',
        redirect: '/hierarchy',
        beforeEnter: ifNotAuthenticated,
      },
      {
        path: '/hierarchy',
        components: {
            appComponent: AppComponent
        },
        name: 'Hierarchy',
        beforeEnter: ifNotAuthenticated,
      },
      {
        path: '/list',
        component: ListComponent,
        name: 'List',
        beforeEnter: ifNotAuthenticated,
      },
      {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter: ifNotAuthenticated,
      },
      {
        path: '/register',
        name: 'register',
        component: Register,
        beforeEnter: ifNotAuthenticated,
      },
      {
        path: '/password/reset/',
        name: 'password-email',
        component: PasswordEmail,
        beforeEnter: ifNotAuthenticated,
      },
      {
        path: '/password/reset/:token',
        name: 'password-reset',
        component: PasswordReset,
        beforeEnter: ifNotAuthenticated,
      },
      {
        path: '/home',
        name: 'home',
        component: HomeLayout,
        beforeEnter: ifAuthenticated,
      },
      {
        path: '/home/employee/create',
        component: CreateEmployee,
        name: 'createEmployee',
        beforeEnter: ifAuthenticated,
      },
      {
        path: '/home/employee/edit/:id',
        component: EditEmployee,
        name: 'editEmployee',
        beforeEnter: ifAuthenticated,
      },
     ]

export const router = new VueRouter({
 mode: 'history',
 routes
});

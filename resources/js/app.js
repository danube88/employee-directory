require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

//import ExampleComponent from './components/ExampleComponent.vue';
import HierarchyComponent from './components/HierarchyComponent.vue';
import ListComponent from './components/ListComponent.vue';
import { library } from '@fortawesome/fontawesome-svg-core'
import { faFolderPlus,faFolderMinus,faFolder } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
library.add(faFolderPlus)
library.add(faFolderMinus)
library.add(faFolder)

Vue.component('font-awesome-icon', FontAwesomeIcon)

const routes = [
      {
        path: '/',
        redirect: '/hierarchy'
      },
      {
         path: '/hierarchy',
         components: {
             hierarchyComponent: HierarchyComponent
         },
         name: 'Hierarchy'
      },
      {
         path: '/list',
         component: ListComponent,
         name: 'List'
      }
     ]

const router = new VueRouter({ routes })

const app = new Vue({ router }).$mount('#app')

import {createRouter, createWebHistory} from 'vue-router';

const routes = [
    {   path: '/start',
        component: () => import('./components/StartPageComponent.vue'), name:'start'
    },
    {   path: '/signup',
        component: () => import('./components/Auth/SignUpComponent.vue'), name:'signUp'
    },
    {   path: '/login',
        component: () => import('./components/Auth/LoginComponent.vue'), name:'login'
    },
    {   path: '/lot/:id',
        component: () => import('./components/Lot/LotComponent.vue'), name:'show'
    },
    {   path: '/user',
        component: () => import('./components/User/UserComponent.vue'), name:'user'
    },
    {   path: '/user/:id',
        component: () => import('./components/User/UserShowSellerComponent.vue'), name:'user.show.seller'
    },
    {   path: '/user/lot/create',
        component: () => import('./components/User/UserLotCreateComponent.vue'), name:'create'
    },
    {   path: '/user/edit',
        component: () => import('./components/User/UserEditComponent.vue'), name:'user.edit'
    },
    {   path: '/admin',
        component: () => import('./components/Admin/AdminComponent.vue'), name:'admin'
    },
    {   path: '/admin/lots',
        component: () => import('./components/Admin/AdminLotsShowComponent.vue'), name:'admin.lots'
    },
    {   path: '/admin/users',
        component: () => import('./components/Admin/AdminUsersShowComponent.vue'), name:'admin.users'
    },
    {   path: '/admin/user/:id',
        component: () => import('./components/Admin/AdminUserComponent.vue'), name:'admin.user'
    },
    {   path: '/user/restore',
        component: () => import('./components/User/UserRestoreComponent.vue'), name:'user.restore'
    },
    {   path: '/user/lots/own',
        component: () => import('./components/User/UserLotsComponent.vue'), name:'user.lots'
    },
    {   path: '/search/lots',
        component: () => import('./components/User/UserSearchLotsComponent.vue'), name:'search.lots'
    },
    {   path: '/user/complaints/:id',
        component: () => import('./components/User/UserComplaintsComponent.vue'), name:'user.complaints'
    },
    {   path: '/admin/complaints',
        component: () => import('./components/Admin/AdminComplaintsComponent.vue'), name:'admin.complaints'
    },
];
const router = createRouter({
    history: createWebHistory('/kurs2.2/public'),
    routes: routes,
});

export default router;

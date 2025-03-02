export const routes = [
  {
    path: '/',
    name: 'Main',
    component: () => import('@/views/main/Main.vue'),
    meta: {needAuth: true},
    children: [

      {
        path: '/admin',
        name: 'Admin',
        component: () => import('@/views/admin/AdminMain.vue'),
        meta: {needIsAdmin: true},
        redirect: '/admin/users',
        children: [
          {
            path: 'users',
            name: 'AdminUsers',
            component: () => import('@/components/admin/AdminUsers.vue')
          },
          {
            path: 'chats',
            name: 'AdminChats',
            component: () => import('@/components/admin/AdminChats.vue')
          }
        ]
      },

      {
        path: '/chats',
        name: 'Chats',
        component: () => import('@/views/chat/ChatsAll.vue'),
        children: [
          {
            path: ':chatId',
            name: 'ChatItem',
            component: () => import('@/views/chat/Chat.vue')
          }
        ]
      },
      {
        path: 'profile',
        name: 'Profile',
        component: () => import('@/views/profile/Profile.vue')
      }
    ]
  },

  {
    path: '/auth',
    name: 'Auth',
    component: () => import('@/views/auth/Auth.vue'),
    meta: {needAuth: false}
  },

  {
    path: '/:pathMatch(.*)*',
    name: 'PageNotFound',
    component: () => import('@/views/errorPages/PageNotFound.vue')
  }


]

import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import AboutView from '@/views/AboutView.vue'
import NotFound from '@/views/NotFound.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: {
        title: 'Lightning 3'
      }
    },
    {
      path: '/about',
      name: 'about',
      component: AboutView,
      meta: {
        title: 'About Lightning 3'
      }
    },
    {
      path: '/:pathMatch(.*)',
      name: 'not-found',
      component: NotFound,
      meta: {
        title: 'Uh oh!'
      },
    },
  ],
})

router.beforeEach((to, from, next) => {
  const title = to.meta?.title

  if (title) {
    document.title = title
  }

  next()
})

export default router

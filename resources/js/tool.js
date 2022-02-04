Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-webhooks',
      path: '/nova-webhooks',
      component: require('./components/Tool.vue'),
    },
  ])
})

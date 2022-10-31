export default ({ store, redirect, route, $checkPermission }) => {
  if (!store.getters['auth/check']) {
    alert('Akses terbatas')
    return redirect('/')
  }
  for (let i = 0; i < route.meta.length; i++) {
    if (route.meta[i].allow_permissions) {
      if (!$checkPermission(route.meta[i].allow_permissions)) {
        alert('Akses terbatas')
        return redirect('/')
      }
    }
  }
}

export default ({ store, redirect, route }) => {
  if (!store.getters['auth/check']) {
    window.prev_url = route.path
    return redirect('/login')
  }
}

function isIntersect(arr1, arr2) {
    if (!arr1) return false
    for (let i = 0; i < arr1.length; i++) {
        if (arr2.indexOf(arr1[i]) > -1) return true
    }
    return false
}

export default (context, inject) => {
    const checkPermission = (permission) => {
        if (Array.isArray(permission)) {
            return isIntersect(context.store.getters['auth/user'].user_permissions, permission)
        }
        return context.store.getters['auth/user'] ? context.store.getters['auth/user'].user_permissions.indexOf(permission) > -1 : false
    }
    // Inject $hello(msg) in Vue, context and store.
    inject('checkPermission', checkPermission)
    // For Nuxt <= 2.12, also add 👇
    context.$checkPermission = checkPermission
  }
  
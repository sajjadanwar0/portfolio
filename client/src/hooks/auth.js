import useSWR from 'swr'
import axios from '@/lib/axios'
import {useEffect} from 'react'
'use client';
import { useRouter } from 'next/navigation';
import _, {difference} from 'lodash'

export const useAuth = ({middleware, redirectIfAuthenticated = '/dashboard'} = {}) => {
    const {router} = useRouter()

    const {data: user, error} = useSWR('/api/user', () =>
        axios
            .get('/api/user')
            .then(res => res.data)
            .catch(error => {
                if (error?.response?.status !== 409) throw error

                router.push('/verify-email')
            }),
    )

    const csrf = () => axios.get('/sanctum/csrf-cookie')

    // const register = async ({ setErrors, ...props }) => {
    //     await csrf()
    //
    //     axios
    //         .post('/register', props)
    //         .catch(error => {
    //             if (error.response.status !== 422) throw error
    //
    //             setErrors(Object.values(error.response.data.errors))
    //         })
    //         .then(() => revalidate())
    // }

    const login = async ({setErrors, ...props}) => {
        await csrf()

        axios
            .post('/login', props)
            .catch(error => {
                if (error?.response?.status !== 422) throw error

                setErrors(Object.values(error?.response?.data.errors))
            })
    }

    const forgotPassword = async ({setErrors, setStatus, email}) => {
        await csrf()

        axios
            .post('/forgot-password', {email})
            .catch(error => {
                if (error.response.status !== 422) throw error

                setErrors(Object.values(error.response.data.errors))
            })
            .then(response => setStatus(response.data.status))
    }

    const resetPassword = async ({setErrors, setStatus, ...props}) => {
        await csrf()

        axios
            .post('/reset-password', {token: router.query.token, ...props})
            .catch(error => {
                if (error.response.status !== 422) throw error

                setErrors(Object.values(error.response.data.errors))
            })
            .then(response => setStatus(response.data.status))
    }

    const resendEmailVerification = ({setStatus}) => {
        axios
            .post('/email/verification-notification')
            .then(response => setStatus(response.data.status))
    }

    const logout = async () => {
        if (!error) {
            await axios.post('/logout')
        }

        window.location.pathname = '/login'
    }

    useEffect(() => {
        if (middleware === 'guest' && redirectIfAuthenticated && user)
            router.push(redirectIfAuthenticated)
        if (middleware === 'auth' && error) logout()
    }, [user, error])

    /**
     * Check if user is authenticated
     */
    const check = () => {
        return !_.isEmpty(user)
    }

    /**
     *Check if user has verified email
     */
    const verifiedEmail = () => {
        return check() && user?.email_verified_at
    }

    /**
     * Get super admin role
     */
    const isSuperAdmin = () => {
        if (!check()) return false
        const roles = user.all_roles
        return roles.includes('admin')
    }

    /**
     * Check if user has role
     */
    const hasRole = (value) => {
        if (!check()) return false
        const roles = user?.all_roles
        if (typeof value === 'string') {
            return roles.includes(value.trim())
        }
        return roles.includes(value)
    }

    /**
     * Check if user has any role
     */
    const hasAnyRole = (value) => {
        const roles = user.all_roles
        if (!check()) return false
        if (typeof value === 'string') {
            return roles.includes(value.trim())
        }
        return !!roles.length
    }

    /**
     * Check if user has all role
     */
    const hasAllRole = (value) => {
        if (!check()) return false
        const roles = user.all_roles
        return difference(value, roles).length === 0
    }

    /**
     * Check if user has permission
     */
    const can = (value) => {
        if (!check()) return false
        const permissions = user.all_permissions
        if (!_.isEmpty(permissions) && typeof value === 'string') {
            return permissions.includes(value.trim())
        }
        return permissions.includes(value)
    }

    /**
     * Check if user does not have permission
     */
    const cannot = (value) => {
        return !can(value)
    }

    return {
        user,
        // register,
        login,
        forgotPassword,
        resetPassword,
        resendEmailVerification,
        logout,
        check,
        verifiedEmail,
        hasRole,
        hasAnyRole,
        hasAllRole,
        isSuperAdmin,
        can,
        cannot,
    }
}
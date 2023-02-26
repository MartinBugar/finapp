import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Register from '../pages/Auth/Register.vue';
import Login from '../pages/Auth/Login.vue';
import AdminLogin from '../pages/Auth/AdminLogin.vue';
import Dashboard from '../pages/Dashboard';
import Posts from '../components/Income/Posts.vue';
import EditPost from '../components/Income/EditPost.vue';
import AddPost from '../components/Income/AddPost.vue';
import Expenses from "../components/Expenses/Expenses.vue";
import AddExpens from "../components/Expenses/AddExpens.vue";
import EditExpens from "../components/Expenses/EditExpens.vue";
import ExpensesTypes from "../components/ExpensesTypes/ExpensesTypes.vue";
import AddExpensType from "../components/ExpensesTypes/AddExpensType.vue";
import EditExpensType from "../components/ExpensesTypes/EditExpensType.vue";
import Loans from "../components/loans/Loans.vue";
import AddLoan from "../components/loans/AddLoan.vue";
import EditLoanDetail from "../components/loans/LoanDetail/EditLoanDetail.vue";
import AddLoanDetail from "../components/loans/LoanDetail/AddLoanDetail.vue";
import LoanDetail from "../components/loans/LoanDetail/LoanDetail.vue";
import EditLoan from "../components/loans/EditLoan.vue";
import AdminPanel from "../pages/AdminPanel.vue";
import UserProfile from "../components/UserProfile/UserProfile.vue";
import EditUserProfile from "../components/UserProfile/EditUserProfile.vue";
import ContactUs from "../pages/ContactUs.vue";
import AboutUs from "../pages/AboutUs.vue";
import VerifyEmail from "../pages/Auth/VerifyEmail.vue";
import CheckYourEmail from "../pages/Auth/CheckYourEmail.vue";


export const routes = [
    {
        name: 'aboutus',
        path: '/aboutus',
        component: AboutUs
    },
    {
        name: 'contactus',
        path: '/contactus',
        component: ContactUs
    },
    {
        name: 'adminlogin',
        path: '/adminlogin',
        component: AdminLogin
    },
    {
        name: 'userprofile',
        path: '/userprofile',
        component: UserProfile
    },
    {
        name: 'userprofile',
        path: '/userprofile',
        component: UserProfile
    },
    {
        name: 'edituserprofile',
        path: '/edituserprofile/:id',
        component: EditUserProfile
    },
    {
        name: 'adminpanel',
        path: '/adminpanel',
        component: AdminPanel
    },
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'posts',
        path: '/posts',
        component: Posts
    },
    {
        name: 'addpost',
        path: '/posts/add',
        component: AddPost
    },
    {
        name: 'editpost',
        path: '/posts/edit/:id',
        component: EditPost
    },
    {
        name: 'expenses',
        path: '/expenses',
        component: Expenses
    },
    {
        name: 'addexpens',
        path: '/expenses/add',
        component: AddExpens
    },
    {
        name: 'editexpens',
        path: '/expenses/edit/:id',
        component: EditExpens
    },
    {
        name: 'expensestypes',
        path: '/expensestypes',
        component: ExpensesTypes
    },
    {
        name: 'addexpensestypes',
        path: '/expensestypes/add',
        component: AddExpensType
    },
    {
        name: 'editexpensestypes',
        path: '/editexpensestypes/edit/:id',
        component: EditExpensType
    },
    {
        name: 'verifyemail',
        path: '/verify-email',
        component: VerifyEmail
    },
    {
        name: 'CheckYourEmail',
        path: '/checkEmail',
        component: CheckYourEmail
    },
    {
        name: 'loans',
        path: '/loans',
        component: Loans
    },
    {
        name: 'addloan',
        path: '/loans/add',
        component: AddLoan
    },
    {
        name: 'editloan',
        path: '/loans/edit/:id',
        component: EditLoan
    },
    {
        name: 'loanDetail',
        path: '/loans/loanDetail/:id',
        component: LoanDetail
    },
    {
        name: 'addloanDetail',
        path: '/loans/loanDetail/add',
        component: AddLoanDetail
    },
    {
        name: 'editloanDetail',
        path: '/loans/loanDetail/edit/:id',
        component: EditLoanDetail
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;

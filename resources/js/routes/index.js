// BEGIN:Main Routes
import MainHome from '../components/Main/Home.vue'
const main = [
    {
        path: '/',
        name: 'main-home',
        component: MainHome
    }
];
// END:Main Routes

// BEGIN:Admin Routes
import AdminUsers from '../components/Admin/Users.vue'
import AdminTexts from '../components/Admin/Texts.vue'
import AdminSettings from '../components/Admin/Settings.vue'
const admin = [
    {
        path: '/admin/users',
        name: 'admin-users',
        component: AdminUsers
    },
    {
        path: '/admin/texts',
        name: 'admin-texts',
        component: AdminTexts
    },
    {
        path: '/admin/settings',
        name: 'admin-settings',
        component: AdminSettings
    },
]
// END:Admin Routes

// BEGIN:Texts Routes
import TextsTagging from '../components/Texts/Tagging.vue'
import TextsDrafts from '../components/Texts/Drafts.vue'
import TextsTagged from '../components/Texts/Tagged.vue'
import TextsStatistics from '../components/Texts/Statistics.vue'
const texts = [
    {
        path: '/tagging',
        name: 'texts-tagging',
        component: TextsTagging
    },
    {
        path: '/drafts',
        name: 'texts-drafts',
        component: TextsDrafts
    },
    {
        path: '/tagged',
        name: 'texts-tagged',
        component: TextsTagged
    },
    {
        path: '/statistics',
        name: 'texts-statistics',
        component: TextsStatistics
    }
]
// END:Texts Routes

// BEGIN:Support Routes
import SupportContact from '../components/Support/Contact.vue'
const support = [
    {
        path: '/support',
        name: 'support-contact',
        component: SupportContact
    }
]
// END:Support Routes

// BEGIN:Erros
import PageNotFound from '../components/Errors/PageNotFound.vue'
const errors = [
    {
        path: '*',
        component: PageNotFound
    }
]

const routes = [].concat(main, admin, texts, support, errors);

export default routes;
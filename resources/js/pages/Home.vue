<template>
    <div class="all-home">
        <header class="home-header">
            <div class="container header-list ">
                <h1><a href="#posts">Prehľad priíjmov</a></h1>
                <h1><a href="#expenses">Prehľad výdajov</a></h1>
                <h1><a href="#dashboard">Celkový prehľad financií</a></h1>

            </div>
        </header>
        <div class="home-page">
            <div class="container">
                <section class="features">
                    <article id="posts" class="feature">
                        <figure>
                            <img class="image box" src="../../images/image1.jpg" alt=""/>
                        </figure>
                        <div class="content">
                            <h2 class="header2">Spravte si prehľad svojich príjmov</h2>
                            <h3 class="header3">Za daný mesiac</h3>
                            <p class="paragraph">V tabuľke si môžete trackovať svoje príjmy</p>
                            <!--                            <p class="paragraph">Toto je uplne iny text ktory tioez dopnim</p>-->
                        </div>
                    </article>

                    <article id="expenses" class="feature">
                        <figure>
                            <img class="image box" src="./../../images/image2.jpg" alt=""/>
                        </figure>
                        <div class="content">
                            <h2 class="header2">Spravte si prehľad svojich výdavkov</h2>
                            <h3 class="header3">Za daný mesiac</h3>
                            <p class="paragraph">V tabuľke si môžete trackovať svoje výdavky</p>
                            <!--                            <p class="paragraph">Toto je uplne iny text ktory tioez dopnim</p>-->
                        </div>
                    </article>

                    <article id="dashboard" class="feature">
                        <figure>
                            <img class="image box" src="./../../images/image3.jpg" alt=""/>
                        </figure>
                        <div class="content">
                            <h2 class="header2">Spravte si celkový prehľad svojich financií</h2>
                            <h3 class="header3">Za daný rok</h3>
                            <p class="paragraph">V tabuľke si môžete trackovať financie</p>
                            <!--                            <p class="paragraph">Toto je uplne iny text ktory tioez dopnim</p>-->
                        </div>
                    </article>
                </section>
            </div>
        </div>
    </div>

</template>

<script>

import {Chart as ChartJS, ArcElement, Tooltip, Legend} from 'chart.js'
import {Pie} from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
    name: "Home",
    components: {
        Pie
    },
    data() {
        return {}
    },
    created() {
    },
    methods: {}
}
</script>

<style>

:root {
    --bg-secondary: #021f2d;
    --cl-accent: #2071cc;
    --max-width-1: 1200px;
    --max-width-2: 950px;
    --max-width-3: 900px;
    --max-width-4: 850px;
    --max-width-5: 800px;

    --bg-vh: --bg-ee + --bg-aa;
    --bg-ee: 100vh;
    --bg-aa: 400px;
}

/*///////////////////////////*/
figure {
    margin-bottom: 4rem;
}

.image {
    border-style: none;
    border-radius: 18px;
    max-width: 100%;
    height: auto;
}

.paragraph {
    margin: 1rem 0 0;
    font-size: 1.25rem;
    font-family: Tahoma, Verdana, sans-serif;
}

.home-page .container {
    /*max-width: 1170px;*/
    color: #ffffff;
    background: var(--bg-secondary);
    font-family: 'Bebas Neue', cursive;
    margin: 0 auto;
}

.home-page {
    background-color: var(--bg-secondary);
    position: relative;
}

.header2 {
    line-height: 1;
    margin: 0;
    /*font-size: 2rem;*/
    font-size: clamp(1.75rem, 5vw, 2.5rem);
}

.header3 {
    line-height: 1;
    margin: 0;
    font-size: clamp(1.75rem, 2vw, 2.5rem);
}

.feature {
    /*margin: 4rem 0;*/
}

.feature h2 {
    color: var(--cl-accent);
    text-shadow: 2px 2px 0 #000;
}

.feature h3 {
    text-shadow: 2px 2px 0 #000;
}

.feature .content {
    position: relative;
    /*bottom: 20rem;*/
}

/*RESPONSIVE MEDIA QUEERRIES*/

@media screen and (min-width: 810px) {

    .home-page .container {
        padding: 5rem 0;
    }

    .feature {
        display: flex;
        align-items: center;
        gap: 3rem;
        border-right: 0.75rem solid var(--cl-accent);
        margin-bottom: 2rem;
        padding: 1rem 0;
    }

    .feature:nth-child(even) {
        flex-direction: row-reverse;
        border-left: 0.75rem solid var(--cl-accent);
        border-right: none;
    }

    .feature figure {
        flex: 3;
        position: static;
    }

    .feature .content {
        flex: 1;
        position: static;
        padding: 0 2rem;

    }
}

.home-header {
    min-height: 100vh;
    background-image: url("./../../images/header.jpg");
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: 50% 50%;
}

.home-page:before {
    content: '';
    background-image: linear-gradient(319deg, var(--bg-secondary) 16px, transparent 17px),
    linear-gradient(39deg, var(--bg-secondary) 16px, transparent 17px);
    background-size: 20px 23px;
    background-repeat: repeat-x;
    display: block;
    width: 100%;
    height: 100px;

    position: absolute;
    top: -23px;
}

.box {
    box-shadow: 0 1px 1px hsl(0deg 0% 0% / 0.375),
    0 2px 2px hsl(0deg 0% 0% / 0.375),
    0 4px 4px hsl(0deg 0% 0% / 0.375),
    0 8px 8px hsl(0deg 0% 0% / 0.375),
    0 16px 16px hsl(0deg 0% 0% / 0.375);
}

.header-list {
    color: white;
    font-family: "Bebas Neue";
    padding-top: 120px;
}

.header-list a {
    font-size: 4rem;
    text-decoration: none;
    color: white;
}

</style>

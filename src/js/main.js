//import barba from '@barba/core';
import axios from 'axios';
import { gsap } from 'gsap';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin.js';
import { cacheAdapterEnhancer } from 'axios-extensions';
import imagesLoaded from 'imagesloaded';

gsap.registerPlugin(ScrollToPlugin);

// if (location.protocol !== 'https:') {
//     location.replace(`https:${location.href.substring(location.protocol.length)}`);
// }

const http = axios.create({
    baseURL: '/',
    headers: { 'Cache-Control': 'no-cache' },
    // cache will be enabled by default
    adapter: cacheAdapterEnhancer(axios.defaults.adapter)
});

let h;
let scrollValues = {};


const getCookie = (cname)=>{
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

const setCookie = (cname, cvalue, exdays)=>{
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

let openPages = getCookie('openPages') ? getCookie('openPages').split(',') : []


const fadeInImgs = () => {
    const fadeInImgs = document.querySelectorAll('.imgwrap');
    if(fadeInImgs){
        fadeInImgs.forEach((fadeInImg)=>{
            imagesLoaded( fadeInImg, function() {
                fadeInImg.classList.add('loaded');
            });            
        })
    }    
}
fadeInImgs()


function pageFunctions(){
    const subPageNavLinks = document.querySelectorAll('.subpage-nav a');
    if(subPageNavLinks){
        subPageNavLinks.forEach((subPageNavLink)=>{
            subPageNavLink.addEventListener('click',(e)=>{
                e.preventDefault();
                let scrollToId = subPageNavLink.getAttribute('href')
                if(document.querySelector(scrollToId)){
                    gsap.to(document.body, {duration:.5, scrollTo: scrollToId })
                }

            });
        })
    }
    const openPanelLinks = document.querySelectorAll('.open-panel');
    if(openPanelLinks){
        openPanelLinks.forEach((openPanelLink)=>{
            openPanelLink.addEventListener('click',(e)=>{
                e.preventDefault();
                document.querySelector(openPanelLink.getAttribute('href')).classList.add('open')
            });
        })
    }
    const closePanelLinks = document.querySelectorAll('.close-panel');
    if(closePanelLinks){
        closePanelLinks.forEach((closePanelLink)=>{
            closePanelLink.addEventListener('click',(e)=>{
                e.preventDefault();
                closePanelLink.parentElement.classList.remove('open')
            });
        })
    }
    fadeInImgs()
}
pageFunctions()

function updateUrl(href,data){
    var title = data.querySelector('title').innerText;
    window.history.pushState({path:href},'',href);
    document.title = title;
    scrollValues[window.location.href] = document.body.scrollTop;
}

const openPopups = () => { 
    const popupLinks = document.querySelectorAll('.open-popup')
    popupLinks.forEach((popupLink)=>{
        popupLink.addEventListener('mouseover',()=>{
            http.get(popupLink.href);
        });    
        popupLink.addEventListener('click',(e)=>{ 
            e.preventDefault()
            http.get(popupLink.href).then(function (html) {
                const doc = document.createElement("div");
                doc.innerHTML = html.data;
                const popupContent = doc.querySelector('.popup-page');

                document.querySelector('main').appendChild(popupContent);
                const tl = gsap.timeline({
                    onComplete: ()=>{
                        closePopups()
                        fadeInImgs()
                        paginationFunc()
                        openPopups()
                    }
                });

                tl.from(popupContent, {
                    duration: .3,
                    opacity: 0
                }); 

                updateUrl(popupLink.href,doc)

            });          
        }); 
    });          
} 
openPopups() 

function filterFunc(){ 
    const filters = document.querySelectorAll('a.filter')
    filters.forEach((filter)=>{
        filter.addEventListener('mouseover',()=>{
            http.get(filter.href);
        });    
        filter.addEventListener('click',(e)=>{ 
            e.preventDefault()

            const sectionWrap = filter.closest('.section-wrap');
            const sectionContent = sectionWrap.querySelector('.section-content');
            const filterContent = sectionWrap.querySelectorAll('.filter-content');

            sectionContent.style.height = sectionContent.clientHeight + 'px'

            const tl = gsap.timeline({
                onComplete: ()=>{ 

                    http.get(filter.href).then(function (html) {
                        const doc = document.createElement("div");
                        doc.innerHTML = html.data;

                        const sectionContentEl = doc.querySelector('#' + sectionWrap.id + ' .section-content');

                        var fillCont = sectionContentEl.getElementsByClassName('filter-content');
                        for (var i = 0; i < fillCont.length; i++ ) {
                            fillCont[i].style.opacity = 0;
                        }

                        const sectionContentHtml = sectionContentEl.innerHTML;

                        sectionContent.innerHTML = sectionContentHtml;
                        const filterContent = sectionContent.querySelectorAll('.filter-content');

                        const tl2 = gsap.timeline({
                            onComplete: ()=>{
                                filterFunc()
                                fadeInImgs()
                                mobileFilters()
                                openPaginationFunc()
                            }
                        });

                        //console.log(filter, filter.href)

                        updateUrl(filter.href,doc)

                        if(sectionWrap.querySelector('.section-link')){
                            sectionWrap.querySelector('.section-link').href = filter.href
                        }

                        setCookie('filters-'+sectionWrap.id,filter.href,30)

        
                        tl2.to(sectionContent, {
                            duration: 1,
                            height: "auto",
                            ease: "linear"
                        }).to(filterContent,{
                            duration: .3,
                            opacity: 1
                        });
                    }); 
  
                }
            });

            tl.to(filterContent, {
                duration: .3,
                opacity: 0
            });        
        }); 
    });          
} 
filterFunc()


function openPaginationFunc(){ 
    const pageBtns = document.querySelectorAll('.open-pagination-popup a')
    pageBtns.forEach((pageBtn)=>{
        pageBtn.addEventListener('mouseover',()=>{
            http.get(pageBtn.href);
        });    
        pageBtn.addEventListener('click',(e)=>{ 
            e.preventDefault()
            http.get(pageBtn.href).then(function (html) {
                const doc = document.createElement("div");
                doc.innerHTML = html.data;
                const popupContent = doc.querySelector('.popup-page');

                document.querySelector('main').appendChild(popupContent);
                const tl = gsap.timeline({
                    onComplete: ()=>{
                        closePopups()
                        paginationFunc()
                        fadeInImgs()
                        filterFunc()
                        mobileFilters()
                    }
                });

                updateUrl(pageBtn.href,doc)

                tl.from(popupContent, {
                    duration: .3,
                    opacity: 0
                }); 
            }); 
                   
        }); 
    });          
} 
openPaginationFunc()

function paginationFunc(){ 
    const pageBtns = document.querySelectorAll('.pagination-cont a')
    pageBtns.forEach((pageBtn)=>{
        pageBtn.addEventListener('mouseover',()=>{
            http.get(pageBtn.href);
        });    
        pageBtn.addEventListener('click',(e)=>{ 
            e.preventDefault()
            const sectionWrap = pageBtn.closest('.popup-page');
            const sectionContent = sectionWrap.querySelector('.section-content');
            const filterContent = sectionWrap.querySelectorAll('.filter-content');

            sectionContent.style.height = sectionContent.clientHeight + 'px'

            const tl = gsap.timeline({
                onComplete: ()=>{ 

                    http.get(pageBtn.href).then(function (html) {
                        const doc = document.createElement("div");
                        doc.innerHTML = html.data;

                        const sectionContentEl = doc.querySelector('#' + sectionWrap.id + ' .section-content');

                        var fillCont = sectionContentEl.getElementsByClassName('filter-content');
                        for (var i = 0; i < fillCont.length; i++ ) {
                            fillCont[i].style.opacity = 0;
                        }

                        const sectionContentHtml = sectionContentEl.innerHTML;

                        sectionContent.innerHTML = sectionContentHtml;
                        const filterContent = sectionContent.querySelectorAll('.filter-content');

                        const tl2 = gsap.timeline({
                            onComplete: ()=>{
                                filterFunc()
                                fadeInImgs()
                                openPaginationFunc()
                                mobileFilters()
                                paginationFunc()
                            }
                        });

                        updateUrl(pageBtn.href,doc)

                        tl2.to(sectionContent, {duration: 1,height: "auto",ease: "linear"})
                           .to(sectionWrap, {duration:.6, scrollTo: 0 },">")
                           .to(filterContent,{duration: .3,opacity: 1});
                    }); 
  
                }
            });

            tl.to(filterContent, {
                duration: .3,
                opacity: 0
            }); 
                   
        }); 
    });          
} 
paginationFunc()

const closePopups = () => { 
    const popupLinks = document.querySelectorAll('.close-popup')
    popupLinks.forEach((popupLink)=>{   
        popupLink.addEventListener('click',(e)=>{ 
            e.preventDefault()

            const popupContent = popupLink.closest('.popup-page')

            const tl = gsap.timeline({
                onComplete: ()=>{ 
                    popupContent.remove()
                }
            }); 

            http.get(popupLink.href).then(function (html) {
                const doc = document.createElement("div");
                doc.innerHTML = html.data;
                updateUrl(popupLink.href,doc)
            });

            tl.to(popupContent, {
                duration: .3,
                opacity: 0
            }); 
         
        }); 
    });          
} 
closePopups() 

const openSub = document.querySelector('a[href="#subscribe"]')
openSub.addEventListener('click',(e)=>{
    e.preventDefault()
    const subForm = document.querySelector('.subform')
    const tl = gsap.timeline(); 
    subForm.style.display = "block"
    tl.to(subForm, {
        duration: .3,
        opacity: 1
    }); 
})
const closeSub = document.querySelector('.close-subform')
closeSub.addEventListener('click',(e)=>{
    //e.preventDefault()
    const subForm = document.querySelector('.subform')
    const tl = gsap.timeline({
        onComplete: ()=>{ 
            subForm.style.display = "none"
        }
    }); 
    tl.to(subForm, {
        duration: .3,
        opacity: 0
    }); 
})

function mobileFilters(){
    const mobileFilters = document.querySelectorAll('.mobile-filters .filters-wrap')
    if(mobileFilters){
        mobileFilters.forEach((mobileFilter)=>{
            const filtersLabel = mobileFilter.querySelector('.label')
            filtersLabel.addEventListener('click',()=>{
                mobileFilter.classList.toggle('open')
            })

        })
    }
}
mobileFilters()

function mobSubpageNav(){
    const subpageNavs = document.querySelectorAll('.subpage-nav')
    if(subpageNavs){
        subpageNavs.forEach((subpageNav)=>{
            const subpageNavLabel = subpageNav.querySelector('.toggle-menu-mob')
            subpageNavLabel.addEventListener('click',()=>{
                subpageNav.classList.toggle('open')
            })

        })
    }
}
mobSubpageNav()

const sectionLinks = document.querySelectorAll('.section-link')
if(sectionLinks){
    sectionLinks.forEach((sectionLink)=> {
        sectionLink.addEventListener('mouseover',(e)=>{
            http.get(sectionLink.href);
        });    
        sectionLink.addEventListener('click',(e)=>{
            e.preventDefault();
            const sectionWrap = sectionLink.closest('.section-wrap');
            const sectionContentWrap = sectionWrap.querySelector('.section-content');
            
            sectionWrap.classList.add('open'); 

            http.get(sectionLink.href).then(function (html) {
                const doc = document.createElement("div");
                doc.innerHTML = html.data;
                const sectionContent = doc.querySelector('#'+sectionWrap.id +' .section-content > *');

                sectionContentWrap.style.height = 0
                sectionContentWrap.style.opacity = 0

                sectionContentWrap.appendChild(sectionContent);

                updateUrl(sectionLink.href,doc)

                // let openPages = getCookie('openPages') ? getCookie('openPages').split(',') : []
                // openPages.push(sectionWrap.querySelector('.close-section').dataset.path)
                openPages.push(sectionWrap.querySelector('.close-section').dataset.path)
                setCookie("openPages", openPages, 30); 
                
                localStorage.setItem('lastOpened', sectionWrap.id);

                const tl = gsap.timeline({
                    onComplete: ()=>{ 
                        pageFunctions()
                        openPopups()
                        filterFunc()
                        mobileFilters()
                        mobSubpageNav()
                        fadeInImgs()
                        openPaginationFunc()
                    }
                });

                tl.to(sectionContentWrap, {
                    duration: .6,
                    height: "auto"
                }).to(sectionContentWrap, {
                    duration: .3,
                    opacity: 1
                });
            
            });
        })
    });
}    

const closeSectionLinks = document.querySelectorAll('.close-section')

closeSectionLinks.forEach((closeSectionLink)=> {   
    closeSectionLink.addEventListener('click',(e)=>{
        e.preventDefault();
        const sectionWrap = closeSectionLink.closest('.section-wrap');
        const sectionContentWrap = sectionWrap.querySelector('.section-content');
        
        const tl = gsap.timeline({
            onComplete: ()=>{ 
                sectionWrap.classList.remove('open'); 
                sectionContentWrap.innerHTML = '';
            }
        });

        http.get(closeSectionLink.href).then(function (html) {
            const doc = document.createElement("div");
            doc.innerHTML = html.data;
            updateUrl(closeSectionLink.href,doc)
        });

        const closePath = closeSectionLink.dataset.path
        //let openPages = getCookie('openPages') ? getCookie('openPages').split(',') : []
        const i = openPages.indexOf(closePath);
        if (i > -1) {
            openPages.splice(i, 1);
        }
        setCookie("openPages", openPages, 30); 

        //const f = window.innerHeight - (document.querySelector('.section-wrap .section-header').clientHeight * 8) - document.querySelector('.site-footer').clientHeight
        let h = document.body.scrollHeight - sectionContentWrap.clientHeight - document.body.scrollTop
        //console.log("space:", window.innerHeight - h, 'f:',f, document.body.scrollTop)

        if(h < window.innerHeight){    
            let s = document.body.scrollTop - (window.innerHeight - h)

            tl.to(sectionContentWrap, {
                duration: .3,
                opacity: 0
            }).to(document.body, {
                duration:.6, 
                scrollTo: s 
            }).to(sectionContentWrap, {
                duration: .6,
                height: 0,
                ease: "linear"
            },'<');
        }else{
            tl.to(sectionContentWrap, {
                duration: .3,
                opacity: 0
            }).to(sectionContentWrap, {
                duration: .6,
                height: 0,
                ease: "linear"
            });
        }     
    })
});


window.addEventListener('beforeunload', function(e) {
    localStorage.setItem('scrollpos', document.body.scrollTop);
});

if(localStorage.getItem('scrollpos')){
    document.body.scrollTop = localStorage.getItem('scrollpos')
}


/**
 * Simulate a click event.
 * @public
 * @param {Element} elem  the element to simulate a click on
 */
 var simulateClick = function (elem) {
	// Create our event (with options)
	var evt = new MouseEvent('click', {
		bubbles: true,
		cancelable: true,
		view: window
	});
	// If cancelled, don't dispatch our event
	var canceled = !elem.dispatchEvent(evt);

};

window.addEventListener('popstate', function(event) {
    const href = event.state.path
    const links = document.querySelectorAll('a[href="'+href+'"]')

    // if(links.length === 1){
    //     console.log('oen section')
    //     simulateClick(links[0]);
    //     console.log(scrollValues[href])
    //     // if(scrollValues[href]){
    //     //     gsap.to(document.body, {
    //     //         duration:.6, 
    //     //         scrollTo: scrollValues[href] 
    //     //     })
    //     // }
    // }else{
    //     if(links[0].classList.contains('close-section') && localStorage.getItem("lastOpened")){
    //         const link = document.querySelector('#'+localStorage.getItem("lastOpened")+ ' .close-section')
    //         if(link){
    //             simulateClick(link);
    //             // if(scrollValues[href]){
    //             //     gsap.to(document.body, {
    //             //         duration:.6, 
    //             //         scrollTo: scrollValues[href] 
    //             //     })
    //             // }
    //         }
    //     }else{
    //         window.location.href = href
    //     }
    // }
        if(links[0].classList.contains('close-section') && localStorage.getItem("lastOpened")){
            const closeSectionLink = document.querySelector('#'+localStorage.getItem("lastOpened")+ ' .close-section')
            const closePath = closeSectionLink.dataset.path
            const i = openPages.indexOf(closePath);
            if (i > -1) {
                openPages.splice(i, 1);
            }
            setCookie("openPages", openPages, 30); 
            window.location.href = href
        }else{
            window.location.href = href
        }
    
    

}, false);

// barba.init({
//     debug: true,
//     views: [
//         {
//             namespace: 'urban-design-forum-australia',
//             beforeEnter(data) {
//                 const subPageNavLinks = data.next.container.querySelectorAll('.subpage-nav a');
//                 if(subPageNavLinks){
//                     subPageNavLinks.forEach((subPageNavLink)=>{
//                         subPageNavLink.addEventListener('click',(e)=>{
//                             e.preventDefault();
//                             let scrollToId = subPageNavLink.getAttribute('href')
//                             if(document.querySelector(scrollToId)){
//                                 gsap.to(window, {duration:.5, scrollTo: scrollToId })
//                             }

//                         });
//                     })
//                 }
//                 const openPanelLinks = data.next.container.querySelectorAll('.open-panel');
//                 if(openPanelLinks){
//                     openPanelLinks.forEach((openPanelLink)=>{
//                         openPanelLink.addEventListener('click',(e)=>{
//                             e.preventDefault();
//                             document.querySelector(openPanelLink.getAttribute('href')).classList.add('open')
//                         });
//                     })
//                 }
//                 const closePanelLinks = data.next.container.querySelectorAll('.close-panel');
//                 if(closePanelLinks){
//                     closePanelLinks.forEach((closePanelLink)=>{
//                         closePanelLink.addEventListener('click',(e)=>{
//                             e.preventDefault();
//                             closePanelLink.parentElement.classList.remove('open')
//                         });
//                     })
//                 }
//                 //fadeInImgs();
//             }
//         }
//     ],
//     transitions: [
//         {
//             name: 'accordion-open-transition',
//             sync: false,
//             // leave(data) {
//             //     console.log(data.current.url.path,data.next.url.path)
//             //     let openPages = getCookie('openPages') ? getCookie('openPages').split(',') : []
//             //     // if(data.next.url.path === '/'){
                    
//             //     //     // const i = openPages.indexOf(data.current.url.path);
//             //     //     // if (i > -1) {
//             //     //     //     openPages.splice(i, 1);
//             //     //     // }
                    
//             //     //     if (data.current.container.querySelector('.section-wrap.open')) {
                        
//             //     //         const projectContent = data.current.container.querySelector('.section-wrap.open .section-content');
//             //     //         //const nextTop = document.querySelector('.section-header .section-link[href="'+data.next.url.href+'"]');
//             //     //         const tl = gsap.timeline();

//             //     //         // if(data.current.container.querySelector('.section-wrap.open').getBoundingClientRect().top < 0){    
//             //     //         //     return tl.to(window, {
//             //     //         //         duration:1, 
//             //     //         //         scrollTo: 0  
//             //     //         //     }).to(projectContent, {
//             //     //         //         duration: .3,
//             //     //         //         opacity: 0
//             //     //         //     }).to(projectContent, {
//             //     //         //         duration: .6,
//             //     //         //         height: 0,
//             //     //         //         ease: "linear"
//             //     //         //     });
//             //     //         // }else{
//             //     //             return tl.to(projectContent, {
//             //     //                 duration: .3,
//             //     //                 opacity: 0
//             //     //             }).to(projectContent, {
//             //     //                 duration: .6,
//             //     //                 height: 0,
//             //     //                 ease: "linear"
//             //     //             });
//             //     //         //}
//             //     //     }
//             //     // }
//             //     // else{
//             //     //     openPages.push(data.next.url.path)
//             //     // } 
//             //     setCookie("openPages", openPages, 30);   
//             // },
//             enter(data) {
//                 //if (data.next.container.querySelector('.section-wrap.open')) {
//                 //if (data.trigger.closest('.section-wrap').querySelector('.section-content')) {    
//                     //const sectionContent = data.next.container.querySelector('.section-wrap.open .section-content');
//                     //const contentHeight = data.next.container.querySelector('.section-wrap.open .section-content').scrollHeight;

//                     const sectionContent = data.next.container.querySelector('.section-header .section-link[href^="'+data.next.url.href+'"]').closest('.section-wrap').querySelector('.section-content')

//                     console.log(sectionContent);
                    
//                     sectionContent.style.height = 0;
//                     sectionContent.style.opacity = 0;

//                     // let speed = Math.round(sectionContent.scrollHeight/100)/30

//                     const tl = gsap.timeline();
//                     tl.to(sectionContent, {
//                         duration: .6,
//                         height: "auto",
//                         ease: "linear"
//                         //delay: .3
//                     }).to(sectionContent, {
//                         duration: .3,
//                         opacity: 1
//                     });

//                     let openPages = getCookie('openPages') ? getCookie('openPages').split(',') : []
//                     console.log(openPages)
//                     openPages.push(data.next.url.path)
//                     setCookie("openPages", openPages, 30); 

//                 //}
//             }
//         },
//         {
//             name: 'accordion-close-transition',
//             sync: false,
//             // from: {
//             //     namespace: [
//             //         'designer'
//             //     ]
//             // },
//             custom: ({ trigger }) => {
//                 return trigger.classList && trigger.classList.contains('close-section');
//             },   
//             leave(data) {
//                 //if (data.trigger.closest('.section-wrap').querySelector('.section-content')) {
//                     const sectionContent = data.trigger.closest('.section-wrap').querySelector('.section-content')
                    
//                     //const projectContent = data.current.container.querySelector('.section-wrap.open .section-content');
//                     //const sectionContent = data.next.container.querySelector('.section-header .section-link[href^="'+data.next.url.href+'"]').closest('.section-wrap').querySelector('.section-content')

//                     const tl = gsap.timeline();

//                     return tl.to(sectionContent, {
//                         duration: .3,
//                         opacity: 0
//                     }).to(sectionContent, {
//                         duration: .6,
//                         height: 0,
//                         ease: "linear"
//                     });
//                 //}
//             },
//             enter(data){
//                 const sectionWrap = data.next.container.querySelector('.section-header .section-link[href*="'+data.trigger.dataset.path+'"]').closest('.section-wrap')
//                 const sectionContent = sectionWrap.querySelector('.section-content')
                
//                 sectionWrap.classList.remove('open')
//                 sectionContent.style.height = 0
//                 sectionContent.style.opacity = 0

//                 const closePath = data.trigger.dataset.path
//                 let openPages = getCookie('openPages') ? getCookie('openPages').split(',') : []
//                 const i = openPages.indexOf(closePath);
//                 if (i > -1) {
//                     openPages.splice(i, 1);
//                 }
//                 setCookie("openPages", openPages, 30); 
//                 console.log(openPages)
//             }
//         },
//         {
//             name: 'fadein-transition',
//             sync: false,
//             // from: {
//             //     namespace: [
//             //         'designer'
//             //     ]
//             // },
//             custom: ({ trigger }) => {
//                 return trigger.classList && trigger.classList.contains('open-popup');
//             },
//             enter(data) {
//                 if (data.next.container.querySelector('.section-wrap.open')) {
//                     const eventContainer = data.next.container.querySelector('.event-content');
//                     const tl = gsap.timeline();
//                     return tl.from(eventContainer, {
//                         duration: .3,
//                         opacity: 0,
//                         display: "block"
//                     });
//                 }
//             }
//         },
//         {
//             name: 'fadeout-transition',
//             sync: false,
//             // from: {
//             //     namespace: [
//             //         'event'
//             //     ]
//             // },
//             // to: {
//             //     namespace: [
//             //         'public-conversations'
//             //     ]
//             // },
//             custom: ({ trigger }) => {
//                 return trigger.classList && trigger.classList.contains('close-popup');
//             },
//             // leave(data) {
//             //     const eventContainer = data.current.container.querySelector('.event-content');
//             //     const tl = gsap.timeline();
//             //     tl.to(eventContainer, {
//             //         duration: 3,
//             //         opacity: 0,
//             //         display: "block"
//             //     });
//             // },
//             enter(data) {
//                 const eventContainer = data.next.container.querySelector('.event-content');
//                 eventContainer.style.display = "block"
//                 const tl = gsap.timeline();
//                 tl.to(eventContainer, {
//                     duration: .3,
//                     opacity: 0,
//                     display: "block",
//                 }).to(eventContainer, {
//                     display: "none"
//                 });
//             }
//         },
//         {
//             name: 'filter-transition',
//             sync: false,
//             // from: {
//             //     namespace: [
//             //         'public-conversations'
//             //     ]
//             // },
//             // to: {
//             //     namespace: [
//             //         'public-conversations'
//             //     ]
//             // },
//             custom: ({ trigger }) => {
//                 return trigger.classList && trigger.classList.contains('filter');
//             },
//             leave(data) {
//                 const sectionContent = data.current.container.querySelector('.section-wrap.open .section-content');
//                 //const filterContent = sectionContent.querySelector('.filter-content');

//                 h = sectionContent.clientHeight + "px"
//                 sectionContent.style.height = h

//                 document.body.classList.add('filtering')
//             },
//             enter(data) {
//                 const sectionContent = data.next.container.querySelector('.section-wrap.open .section-content');
//                 const filterContent = sectionContent.querySelectorAll('.filter-content');

//                 sectionContent.style.height = h;

//                 const tl = gsap.timeline();
//                 tl.to(sectionContent, {
//                     duration: .3,
//                     height: "auto",
//                     ease: "linear"
//                 });

//                 setTimeout(()=>{
//                     document.body.classList.remove('filtering')
//                 },400)

//                 localStorage.setItem(data.next.container.querySelector('.section-wrap.open').id,  window.location.href);
//             }
//         }                 
//     ]
// });

// barba.hooks.enter(() => {
//     const sections = document.querySelectorAll('.section-wrap')
//     sections.forEach((section)=>{
//         let currFilter = localStorage.getItem(section.id)
//         if(currFilter !== null){
//             section.querySelector('.section-link').href = currFilter
//         }
//     })
// });
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

Alpine.plugin(collapse);
Alpine.plugin(intersect);

window.Alpine = Alpine;

let scrollRevealScrollHandler = null;

function initBlockScrollReveal() {
    const elements = Array.from(document.querySelectorAll('[data-block-reveal]'));
    if (!elements.length) return;

    const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced || typeof IntersectionObserver === 'undefined') {
        elements.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const revealEl = (el) => {
        if (el.classList.contains('is-revealed')) return;
        requestAnimationFrame(() => {
            el.classList.add('is-revealed');
        });
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting || entry.boundingClientRect.top < window.innerHeight * 0.92) {
                    revealEl(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.05,
            rootMargin: '0px 0px -40px 0px',
        }
    );

    const checkVisibleElements = () => {
        const triggerLine = window.innerHeight * 0.92;
        elements.forEach((el) => {
            if (el.classList.contains('is-revealed')) return;
            const rect = el.getBoundingClientRect();
            if (rect.top <= triggerLine) {
                revealEl(el);
                observer.unobserve(el);
            } else {
                observer.observe(el);
            }
        });
    };

    checkVisibleElements();

    if (scrollRevealScrollHandler) {
        window.removeEventListener('scroll', scrollRevealScrollHandler);
    }

    let ticking = false;
    scrollRevealScrollHandler = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(() => {
                checkVisibleElements();
                ticking = false;
            });
        }
    };

    window.addEventListener('scroll', scrollRevealScrollHandler, { passive: true });
    window.addEventListener('resize', checkVisibleElements, { passive: true });
}

document.addEventListener('DOMContentLoaded', initBlockScrollReveal);
document.addEventListener('livewire:navigated', initBlockScrollReveal);

Alpine.start();

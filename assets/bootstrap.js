import { start } from '@hotwired/turbo';


// Démarrer Turbo
start();
const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
));
import umsConfig from '@ums/jslint';

export default [
    ...umsConfig,
    {
        files: [ '**/*.ts', '**/*.tsx' ],
        languageOptions: {
            parserOptions: {
                babelOptions: {
                    presets: [ '@babel/preset-react', '@babel/preset-typescript' ],
                },
            },
        },
    },
];
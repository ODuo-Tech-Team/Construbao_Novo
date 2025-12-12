/**
 * SEO Analyzer - Real-time SEO Analysis (similar to RankMath)
 * CONSTRUBÃO - Admin Panel
 */

class SeoAnalyzer {
    constructor() {
        this.fields = {
            titulo: document.getElementById('titulo'),
            slug: document.getElementById('slug'),
            conteudo: document.getElementById('conteudo'),
            focusKeyword: document.getElementById('focus_keyword'),
            metaTitle: document.getElementById('meta_title'),
            metaDescription: document.getElementById('meta_description'),
            seoScore: document.getElementById('seo_score'),
            seoScoreDisplay: document.getElementById('seoScoreDisplay'),
            seoChecks: document.getElementById('seoChecks')
        };

        this.checks = [];
        this.score = 0;

        this.init();
    }

    init() {
        // Add event listeners for real-time analysis
        const fieldsToWatch = ['titulo', 'slug', 'conteudo', 'focusKeyword', 'metaTitle', 'metaDescription'];

        fieldsToWatch.forEach(field => {
            if (this.fields[field]) {
                this.fields[field].addEventListener('input', () => this.analyze());
                this.fields[field].addEventListener('change', () => this.analyze());
            }
        });

        // Watch TinyMCE content changes
        if (typeof tinymce !== 'undefined') {
            const checkTinyMCE = setInterval(() => {
                const editor = tinymce.get('conteudo');
                if (editor) {
                    editor.on('change keyup', () => {
                        this.analyze();
                    });
                    clearInterval(checkTinyMCE);
                }
            }, 500);
        }

        // Initial analysis
        setTimeout(() => this.analyze(), 1000);
    }

    getContent() {
        // Try TinyMCE first
        if (typeof tinymce !== 'undefined') {
            const editor = tinymce.get('conteudo');
            if (editor) {
                return editor.getContent({ format: 'text' });
            }
        }
        return this.fields.conteudo?.value || '';
    }

    getHtmlContent() {
        if (typeof tinymce !== 'undefined') {
            const editor = tinymce.get('conteudo');
            if (editor) {
                return editor.getContent();
            }
        }
        return this.fields.conteudo?.value || '';
    }

    analyze() {
        this.checks = [];
        let totalPoints = 0;
        let earnedPoints = 0;

        const titulo = this.fields.titulo?.value || '';
        const slug = this.fields.slug?.value || '';
        const conteudo = this.getContent();
        const htmlContent = this.getHtmlContent();
        const keyword = this.fields.focusKeyword?.value?.toLowerCase() || '';
        const metaTitle = this.fields.metaTitle?.value || '';
        const metaDescription = this.fields.metaDescription?.value || '';

        // 1. Focus Keyword defined (10 points)
        totalPoints += 10;
        if (keyword.length >= 3) {
            earnedPoints += 10;
            this.addCheck('success', 'Palavra-chave foco definida');
        } else {
            this.addCheck('error', 'Defina uma palavra-chave foco');
        }

        // 2. Keyword in Title (10 points)
        totalPoints += 10;
        if (keyword && titulo.toLowerCase().includes(keyword)) {
            earnedPoints += 10;
            this.addCheck('success', 'Palavra-chave presente no título');
        } else if (keyword) {
            this.addCheck('error', 'Palavra-chave não encontrada no título');
        } else {
            this.addCheck('warning', 'Defina uma palavra-chave para verificar o título');
        }

        // 3. Title length (10 points)
        totalPoints += 10;
        const titleToCheck = metaTitle || titulo;
        if (titleToCheck.length >= 50 && titleToCheck.length <= 60) {
            earnedPoints += 10;
            this.addCheck('success', `Título com tamanho ideal (${titleToCheck.length} caracteres)`);
        } else if (titleToCheck.length > 0 && titleToCheck.length < 50) {
            earnedPoints += 5;
            this.addCheck('warning', `Título muito curto (${titleToCheck.length}/50-60 caracteres)`);
        } else if (titleToCheck.length > 60) {
            earnedPoints += 5;
            this.addCheck('warning', `Título muito longo (${titleToCheck.length}/50-60 caracteres)`);
        } else {
            this.addCheck('error', 'Título não definido');
        }

        // 4. Meta Description (10 points)
        totalPoints += 10;
        if (metaDescription.length >= 120 && metaDescription.length <= 160) {
            earnedPoints += 10;
            this.addCheck('success', `Meta description com tamanho ideal (${metaDescription.length} caracteres)`);
        } else if (metaDescription.length > 0 && metaDescription.length < 120) {
            earnedPoints += 5;
            this.addCheck('warning', `Meta description curta (${metaDescription.length}/120-160 caracteres)`);
        } else if (metaDescription.length > 160) {
            earnedPoints += 5;
            this.addCheck('warning', `Meta description longa (${metaDescription.length}/120-160 caracteres)`);
        } else {
            this.addCheck('error', 'Meta description não definida');
        }

        // 5. Keyword in Meta Description (10 points)
        totalPoints += 10;
        if (keyword && metaDescription.toLowerCase().includes(keyword)) {
            earnedPoints += 10;
            this.addCheck('success', 'Palavra-chave presente na meta description');
        } else if (keyword && metaDescription) {
            this.addCheck('warning', 'Palavra-chave não encontrada na meta description');
        }

        // 6. Keyword in URL/Slug (10 points)
        totalPoints += 10;
        if (keyword && slug.toLowerCase().includes(keyword.replace(/\s+/g, '-'))) {
            earnedPoints += 10;
            this.addCheck('success', 'Palavra-chave presente na URL');
        } else if (keyword) {
            this.addCheck('warning', 'Considere incluir a palavra-chave na URL');
        }

        // 7. Content Length (10 points)
        totalPoints += 10;
        const wordCount = conteudo.split(/\s+/).filter(w => w.length > 0).length;
        if (wordCount >= 600) {
            earnedPoints += 10;
            this.addCheck('success', `Conteúdo com bom tamanho (${wordCount} palavras)`);
        } else if (wordCount >= 300) {
            earnedPoints += 5;
            this.addCheck('warning', `Conteúdo poderia ser maior (${wordCount}/600+ palavras)`);
        } else if (wordCount > 0) {
            this.addCheck('error', `Conteúdo muito curto (${wordCount}/300+ palavras mínimo)`);
        } else {
            this.addCheck('error', 'Adicione conteúdo ao post');
        }

        // 8. Keyword in first paragraph (10 points)
        totalPoints += 10;
        const firstParagraph = conteudo.split('\n')[0]?.toLowerCase() || '';
        if (keyword && firstParagraph.includes(keyword)) {
            earnedPoints += 10;
            this.addCheck('success', 'Palavra-chave no primeiro parágrafo');
        } else if (keyword && conteudo) {
            this.addCheck('warning', 'Inclua a palavra-chave no primeiro parágrafo');
        }

        // 9. Keyword Density (10 points)
        totalPoints += 10;
        if (keyword && wordCount > 0) {
            const keywordCount = (conteudo.toLowerCase().match(new RegExp(keyword, 'gi')) || []).length;
            const density = (keywordCount / wordCount) * 100;

            if (density >= 1 && density <= 2.5) {
                earnedPoints += 10;
                this.addCheck('success', `Densidade da palavra-chave ideal (${density.toFixed(1)}%)`);
            } else if (density > 0 && density < 1) {
                earnedPoints += 5;
                this.addCheck('warning', `Densidade da palavra-chave baixa (${density.toFixed(1)}%, ideal: 1-2.5%)`);
            } else if (density > 2.5) {
                earnedPoints += 5;
                this.addCheck('warning', `Densidade da palavra-chave alta (${density.toFixed(1)}%, ideal: 1-2.5%)`);
            }
        }

        // 10. Has Headings (5 points)
        totalPoints += 5;
        const hasH2 = htmlContent.includes('<h2') || htmlContent.includes('<H2');
        const hasH3 = htmlContent.includes('<h3') || htmlContent.includes('<H3');
        if (hasH2 || hasH3) {
            earnedPoints += 5;
            this.addCheck('success', 'Subtítulos (H2, H3) presentes no conteúdo');
        } else if (wordCount > 300) {
            this.addCheck('warning', 'Adicione subtítulos (H2, H3) para melhorar a estrutura');
        }

        // 11. Internal Links (5 points)
        totalPoints += 5;
        const hasInternalLinks = htmlContent.includes('href="') || htmlContent.includes("href='");
        if (hasInternalLinks) {
            earnedPoints += 5;
            this.addCheck('success', 'Links encontrados no conteúdo');
        } else if (wordCount > 300) {
            this.addCheck('warning', 'Adicione links internos ou externos ao conteúdo');
        }

        // Calculate final score
        this.score = totalPoints > 0 ? Math.round((earnedPoints / totalPoints) * 100) : 0;

        // Update UI
        this.updateUI();
    }

    addCheck(type, message) {
        this.checks.push({ type, message });
    }

    updateUI() {
        // Update score display
        if (this.fields.seoScore) {
            this.fields.seoScore.value = this.score;
        }

        if (this.fields.seoScoreDisplay) {
            this.fields.seoScoreDisplay.textContent = this.score;
            this.fields.seoScoreDisplay.className = 'seo-score-value';

            if (this.score >= 70) {
                this.fields.seoScoreDisplay.classList.add('good');
            } else if (this.score >= 40) {
                this.fields.seoScoreDisplay.classList.add('ok');
            } else {
                this.fields.seoScoreDisplay.classList.add('bad');
            }
        }

        // Update checks list
        if (this.fields.seoChecks) {
            if (this.checks.length === 0) {
                this.fields.seoChecks.innerHTML = `
                    <p style="color: var(--color-text-muted); text-align: center; padding: var(--spacing-4);">
                        Preencha os campos para ver a análise SEO
                    </p>
                `;
            } else {
                this.fields.seoChecks.innerHTML = this.checks.map(check => `
                    <div class="seo-check">
                        <span class="seo-check-icon ${check.type}">
                            ${this.getIcon(check.type)}
                        </span>
                        <span class="seo-check-text">${check.message}</span>
                    </div>
                `).join('');
            }
        }
    }

    getIcon(type) {
        switch (type) {
            case 'success':
                return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>`;
            case 'warning':
                return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    <line x1="12" x2="12" y1="9" y2="13"></line>
                    <line x1="12" x2="12.01" y1="17" y2="17"></line>
                </svg>`;
            case 'error':
                return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" x2="9" y1="9" y2="15"></line>
                    <line x1="9" x2="15" y1="9" y2="15"></line>
                </svg>`;
            default:
                return '';
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on blog create/edit pages
    if (document.getElementById('postForm')) {
        window.seoAnalyzer = new SeoAnalyzer();
    }
});

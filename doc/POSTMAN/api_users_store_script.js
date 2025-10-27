// Função para gerar CPF válido
function gerarCPF() {
    function randomiza(n) {
        return Math.round(Math.random() * n);
    }

    function mod(dividendo, divisor) {
        return Math.round(dividendo - (Math.floor(dividendo / divisor) * divisor));
    }

    let n = 9;
    let n1 = randomiza(n);
    let n2 = randomiza(n);
    let n3 = randomiza(n);
    let n4 = randomiza(n);
    let n5 = randomiza(n);
    let n6 = randomiza(n);
    let n7 = randomiza(n);
    let n8 = randomiza(n);
    let n9 = randomiza(n);
    let d1 = n9 * 2 + n8 * 3 + n7 * 4 + n6 * 5 + n5 * 6 + n4 * 7 + n3 * 8 + n2 * 9 + n1 * 10;
    d1 = 11 - (mod(d1, 11));
    if (d1 >= 10) d1 = 0;
    let d2 = d1 * 2 + n9 * 3 + n8 * 4 + n7 * 5 + n6 * 6 + n5 * 7 + n4 * 8 + n3 * 9 + n2 * 10 + n1 * 11;
    d2 = 11 - (mod(d2, 11));
    if (d2 >= 10) d2 = 0;

    return `${n1}${n2}${n3}.${n4}${n5}${n6}.${n7}${n8}${n9}-${d1}${d2}`;
}

// Função para gerar telefone
function gerarTelefone() {
    const ddd = Math.floor(Math.random() * 89) + 11; // DDD entre 11 e 99
    const num = Math.floor(Math.random() * 90000) + 10000;
    const num2 = Math.floor(Math.random() * 9000) + 1000;
    return `(${ddd}) ${num}-${num2}`;
}

// Função para gerar celular/WhatsApp
function gerarCelular() {
    const ddd = Math.floor(Math.random() * 89) + 11;
    const num = Math.floor(Math.random() * 90000) + 10000;
    const num2 = Math.floor(Math.random() * 9000) + 1000;
    return `(${ddd}) 9${num}-${num2}`;
}

// Função para gerar CEP
function gerarCEP() {
    const num1 = String(Math.floor(Math.random() * 90000) + 10000);
    const num2 = String(Math.floor(Math.random() * 900) + 100);
    return `${num1}-${num2}`;
}

// Função para gerar data de nascimento (entre 18 e 70 anos)
function gerarDataNascimento() {
    const ano = Math.floor(Math.random() * 52) + 1954; // 1954 a 2006
    const mes = String(Math.floor(Math.random() * 12) + 1).padStart(2, '0');
    const dia = String(Math.floor(Math.random() * 28) + 1).padStart(2, '0');
    return `${ano}-${mes}-${dia}`;
}

// Arrays de nomes, sobrenomes e perfis
const nomes = [
    "João", "Maria", "Pedro", "Ana", "Carlos", "Juliana", "Lucas", "Fernanda",
    "Rafael", "Camila", "Bruno", "Patricia", "Felipe", "Amanda", "Diego", "Beatriz",
    "Rodrigo", "Larissa", "Gustavo", "Mariana", "Thiago", "Gabriela", "Matheus", "Carolina"
];

const sobrenomes = [
    "Silva", "Santos", "Oliveira", "Souza", "Lima", "Pereira", "Costa", "Ferreira",
    "Rodrigues", "Almeida", "Nascimento", "Carvalho", "Ribeiro", "Martins", "Rocha",
    "Alves", "Monteiro", "Mendes", "Barros", "Freitas", "Barbosa", "Pinto", "Moura"
];

const perfis = [
    "Desenvolvedor Full Stack", "Desenvolvedor Front-end", "Desenvolvedor Back-end",
    "Analista de Sistemas", "Engenheiro de Software", "DevOps Engineer",
    "Product Manager", "UX/UI Designer", "Analista de Dados", "Scrum Master",
    "Tech Lead", "Arquiteto de Soluções", "QA Engineer", "Desenvolvedor Mobile"
];

const ruas = [
    "Rua das Flores", "Avenida Paulista", "Rua Augusta", "Rua Oscar Freire",
    "Avenida Brasil", "Rua da Consolação", "Rua Vergueiro", "Avenida Faria Lima",
    "Rua dos Pinheiros", "Avenida Rebouças", "Rua Haddock Lobo", "Rua Teodoro Sampaio"
];

// Gerando dados aleatórios
const nome = nomes[Math.floor(Math.random() * nomes.length)];
const sobrenome1 = sobrenomes[Math.floor(Math.random() * sobrenomes.length)];
const sobrenome2 = sobrenomes[Math.floor(Math.random() * sobrenomes.length)];
const nomeCompleto = `${nome} ${sobrenome1} ${sobrenome2}`;

const username = nome.toLowerCase() + sobrenome1.toLowerCase() + Math.floor(Math.random() * 1000);
const timestamp = Date.now();
const email = `${username}${timestamp}@teste.com.br`;

const numeroRua = Math.floor(Math.random() * 9000) + 100;
const rua = ruas[Math.floor(Math.random() * ruas.length)];

// Definindo variáveis de ambiente
pm.environment.set("random_name", nomeCompleto);
pm.environment.set("random_cpf", gerarCPF());
pm.environment.set("random_whatsapp", gerarCelular());
pm.environment.set("random_user", username);
pm.environment.set("random_password", "senha" + Math.floor(Math.random() * 100000));
pm.environment.set("random_profile", perfis[Math.floor(Math.random() * perfis.length)]);
pm.environment.set("random_mail", email);
pm.environment.set("random_phone", gerarTelefone());
pm.environment.set("random_date_birth", gerarDataNascimento());
pm.environment.set("random_zip_code", gerarCEP());
pm.environment.set("random_address", `${rua}, ${numeroRua}`);

console.log("Dados gerados:");
console.log("Nome:", nomeCompleto);
console.log("CPF:", pm.environment.get("random_cpf"));
console.log("Email:", email);
console.log("User:", username);
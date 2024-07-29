import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

import { Container, ConteudoForm, ConteudoTitulo, Titulo, BotaoAcao, ButtonInfo, AlertSuccess, AlertDanger, Form, Label, Input, ButtonWarning } from './styles';

export const Editar = (props) => {

    const [id] = useState(props.match.params.id);
    const [titulo, setTitulo] = useState('');
    const [descricao, setDescricao] = useState('');

    const [status, setStatus] = useState({
        type: '',
        mensagem: ''
    })

    const editProduto = async e => {
        e.preventDefault();

        await fetch("http://localhost/celke/editar.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, titulo, descricao })
        }).then((response) => response.json())
            .then((responseJson) => {
                console.log(responseJson);
                if (responseJson.erro) {
                    setStatus({
                        type: 'erro',
                        mensagem: responseJson.mensagem
                    });
                } else {
                    setStatus({
                        type: 'success',
                        mensagem: responseJson.mensagem
                    });
                }
            }).catch(() => {
                setStatus({
                    type: 'erro',
                    mensagem: "Produto não editado com sucesso, tente mais tarde!"
                });
            });
    }

    useEffect(() => {
        const getProduto = async () => {
            await fetch("http://localhost/celke/visualizar.php?id=" + id)
                .then((response) => response.json())
                .then((responseJson) => {
                    setTitulo(responseJson.produto.titulo);
                    setDescricao(responseJson.produto.descricao);
                });
        }
        getProduto();
    }, [id]);

    return (
        <Container>
            <ConteudoForm>
                <ConteudoTitulo>
                    <Titulo>Editar</Titulo>
                    <BotaoAcao>
                        <Link to="/">
                            <ButtonInfo>Listar</ButtonInfo>
                        </Link>
                    </BotaoAcao>
                </ConteudoTitulo>

                {status.type === 'erro' ? <AlertDanger>{status.mensagem}</AlertDanger> : ""}
                {status.type === 'success' ? <AlertSuccess>{status.mensagem}</AlertSuccess> : ""}


                <Form onSubmit={editProduto}>
                    <Label>Título: </Label>
                    <Input type="text" name="titulo" placeholder="Título do produto" value={titulo} onChange={e => setTitulo(e.target.value)} />

                    <Label>Descrição: </Label>
                    <Input type="text" name="descricao" placeholder="Descrição do produto" value={descricao} onChange={e => setDescricao(e.target.value)} />

                    <ButtonWarning type="submit">Editar</ButtonWarning>
                </Form>

            </ConteudoForm>
        </Container>
    );
}
import {Link} from "react-router-dom";
import React, {MouseEvent, useState} from "react";
import styled from "styled-components";
import {MOBILE_MAX_WIDTH} from "../../Constants";

import * as colour from '../../css/colour'
import * as text from '../../css/text'
import * as padding from '../../css/padding'

const BUTTON_HEIGHT = 2
const MENU_WIDTH = 70

const MobileNavButton = styled.button`
  display: none;
  height: ${BUTTON_HEIGHT}em;
  

  ${MOBILE_MAX_WIDTH} {
    display: block;
    z-index: 1;
    position: fixed;
    top: 0;
  }
`;

const Title = styled.h1`
  ${text.DEFAULT}
  ${text.CENTERED}
  ${colour.TITLE}
  
  margin: 0;
  padding: 15px;
  font-size: 45px;
  
  ${MOBILE_MAX_WIDTH} {
      font-size: 35px;
  }
`;


const GridNav = styled.nav<{ open: boolean }>`
  & > ul {
    display: grid;
    grid-template-columns: repeat(3, minmax(0px, max-content)) 1fr repeat(2, minmax(0px, max-content));
    grid-template-rows: 1fr;
    grid-gap: 10px;
    margin: 0;
    position: relative;
    padding: 0;

    ${MOBILE_MAX_WIDTH} {
      grid-template-columns: 1fr;
      grid-template-rows: repeat(3, minmax(0px, max-content)) 1fr repeat(2, minmax(0px, max-content));
      grid-gap: 15px;

      width: ${MENU_WIDTH}%;
      position: fixed;

      top: 0;
      bottom: 0;
      left: 0;

      transform: translateX(${state => state.open ? 0 : -100}%);
      transition: transform .4s;

      padding: 30px 15px 15px;


      &.open {
        transform: translateX(0);
      }

      background-color: var(--accent-background-colour);
      border-radius: 0 30px 30px 0;
    }
  }
`;

const GridItem = styled.li`
  ${colour.SECTION_TITLE}
  ${text.DEFAULT}
  ${padding.DEFAULT}
  
  
  display: inline;
  text-indent: 0;
  list-style-type: none;
  font-size: 24px;
`;


const MobileNavBackdrop = styled.div<{ open: boolean }>`
  visibility: hidden;
  opacity: 0;

  ${MOBILE_MAX_WIDTH} {
    visibility: ${state => state.open ? "visible" : "hidden"};
    background-color: black;
    opacity: ${state => state.open ? 10 : 0}%;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    transition: opacity 0.3s, secondary-text-colorvisibility 0.3s;
  }
`;

const RouterLink = styled(Link)`
  &:link {
    color: var(--accent-text-colour);
  }

  /* visited link */

  &:visited {
    color: var(--accent-text-colour);
  }

  /* mouse over link */

  &:hover {
    color: var(--secondary-text-colour);
  }
`;

function Navbar() {
    const [isOpen, setOpen] = useState<boolean>(false)

    const sideBarToggle = (_: MouseEvent<HTMLButtonElement | HTMLDivElement>): void => {
        setOpen(!isOpen)
    }

    return (
        <>
            <Title>The Local Theatre</Title>
            <MobileNavButton onClick={sideBarToggle}>☰</MobileNavButton>
            <MobileNavBackdrop onClick={sideBarToggle} open={isOpen}/>

            <GridNav open={isOpen}>
                <ul>
                    <GridItem><RouterLink to="/">Home</RouterLink></GridItem>

                    <GridItem><RouterLink to="/blog">Blog</RouterLink></GridItem>

                    <GridItem><RouterLink to="/contact">Contact Us</RouterLink></GridItem>

                    <div/>

                    <GridItem><RouterLink to="/login">Login</RouterLink></GridItem>

                    <GridItem><RouterLink to="/signup">Signup</RouterLink></GridItem>
                </ul>
            </GridNav>
        </>
    )
}

export default Navbar;
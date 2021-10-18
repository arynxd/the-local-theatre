import styled from "styled-components";

export const MOBILE_WIDTH = 600;

export const MOBILE_MAX_WIDTH = `
    @media screen and (max-width: ${MOBILE_WIDTH}px)
`;

export const DEFAULT_FONT = `
  text-align: center;
  font-weight: bold;
  display: block;
  font-family: 'Rubik', sans-serif;
  color: var(--secondary-text-colour);
`;

export const PageTitle = styled.h1`
  ${DEFAULT_FONT};
  font-size: 40px;
  
  ${MOBILE_MAX_WIDTH} {
    font-size: 30px;
  }
`;
